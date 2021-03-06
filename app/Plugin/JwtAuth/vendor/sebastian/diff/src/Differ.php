<?php
/*
 * This file is part of the Diff package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\Diff;

use SebastianBergmann\Diff\LCS\LongestCommonSubsequence;
use SebastianBergmann\Diff\LCS\MemoryEfficientImplementation;
use SebastianBergmann\Diff\LCS\TimeEfficientImplementation;

/**
 * Diff implementation.
 */
class Differ {
	/**
	 * @var string
	 */
	private $header;

	/**
	 * @var bool
	 */
	private $showNonDiffLines;

	/**
	 * @param string $header
	 */
	public function __construct($header = "--- Original\n+++ New\n", $showNonDiffLines = TRUE) {
		$this->header = $header;
		$this->showNonDiffLines = $showNonDiffLines;
	}

	/**
	 * Returns the diff between two arrays or strings as string.
	 *
	 * @param array|string             $from
	 * @param array|string             $to
	 * @param LongestCommonSubsequence $lcs
	 *
	 * @return string
	 */
	public function diff($from, $to, LongestCommonSubsequence $lcs = NULL) {
		if (!is_array($from) && !is_string($from)) {
			$from = (string)$from;
		}

		if (!is_array($to) && !is_string($to)) {
			$to = (string)$to;
		}

		$buffer = $this->header;
		$diff = $this->diffToArray($from, $to, $lcs);

		$inOld = FALSE;
		$i = 0;
		$old = array();

		foreach ($diff as $line) {
			if ($line[1] === 0 /* OLD */) {
				if ($inOld === FALSE) {
					$inOld = $i;
				}
			} elseif ($inOld !== FALSE) {
				if (($i - $inOld) > 5) {
					$old[$inOld] = $i - 1;
				}

				$inOld = FALSE;
			}

			++$i;
		}

		$start = isset($old[0]) ? $old[0] : 0;
		$end = count($diff);

		if ($tmp = array_search($end, $old)) {
			$end = $tmp;
		}

		$newChunk = TRUE;

		for ($i = $start; $i < $end; $i++) {
			if (isset($old[$i])) {
				$buffer .= "\n";
				$newChunk = TRUE;
				$i = $old[$i];
			}

			if ($newChunk) {
				if ($this->showNonDiffLines === TRUE) {
					$buffer .= "@@ @@\n";
				}
				$newChunk = FALSE;
			}

			if ($diff[$i][1] === 1 /* ADDED */) {
				$buffer .= '+' . $diff[$i][0] . "\n";
			} elseif ($diff[$i][1] === 2 /* REMOVED */) {
				$buffer .= '-' . $diff[$i][0] . "\n";
			} elseif ($this->showNonDiffLines === TRUE) {
				$buffer .= ' ' . $diff[$i][0] . "\n";
			}
		}

		return $buffer;
	}

	/**
	 * Returns the diff between two arrays or strings as array.
	 *
	 * Each array element contains two elements:
	 *   - [0] => string $token
	 *   - [1] => 2|1|0
	 *
	 * - 2: REMOVED: $token was removed from $from
	 * - 1: ADDED: $token was added to $from
	 * - 0: OLD: $token is not changed in $to
	 *
	 * @param array|string             $from
	 * @param array|string             $to
	 * @param LongestCommonSubsequence $lcs
	 *
	 * @return array
	 */
	public function diffToArray($from, $to, LongestCommonSubsequence $lcs = NULL) {
		preg_match_all('(\r\n|\r|\n)', $from, $fromMatches);
		preg_match_all('(\r\n|\r|\n)', $to, $toMatches);

		if (is_string($from)) {
			$from = preg_split('(\r\n|\r|\n)', $from);
		}

		if (is_string($to)) {
			$to = preg_split('(\r\n|\r|\n)', $to);
		}

		$start = array();
		$end = array();
		$fromLength = count($from);
		$toLength = count($to);
		$length = min($fromLength, $toLength);

		for ($i = 0; $i < $length; ++$i) {
			if ($from[$i] === $to[$i]) {
				$start[] = $from[$i];
				unset($from[$i], $to[$i]);
			} else {
				break;
			}
		}

		$length -= $i;

		for ($i = 1; $i < $length; ++$i) {
			if ($from[$fromLength - $i] === $to[$toLength - $i]) {
				array_unshift($end, $from[$fromLength - $i]);
				unset($from[$fromLength - $i], $to[$toLength - $i]);
			} else {
				break;
			}
		}

		if ($lcs === NULL) {
			$lcs = $this->selectLcsImplementation($from, $to);
		}

		$common = $lcs->calculate(array_values($from), array_values($to));
		$diff = array();

		if (isset($fromMatches[0]) && $toMatches[0] &&
				count($fromMatches[0]) === count($toMatches[0]) &&
				$fromMatches[0] !== $toMatches[0]
		) {
			$diff[] = array(
					'#Warning: Strings contain different line endings!', 0
			);
		}

		foreach ($start as $token) {
			$diff[] = array($token, 0 /* OLD */);
		}

		reset($from);
		reset($to);

		foreach ($common as $token) {
			while ((($fromToken = reset($from)) !== $token)) {
				$diff[] = array(array_shift($from), 2 /* REMOVED */);
			}

			while ((($toToken = reset($to)) !== $token)) {
				$diff[] = array(array_shift($to), 1 /* ADDED */);
			}

			$diff[] = array($token, 0 /* OLD */);

			array_shift($from);
			array_shift($to);
		}

		while (($token = array_shift($from)) !== NULL) {
			$diff[] = array($token, 2 /* REMOVED */);
		}

		while (($token = array_shift($to)) !== NULL) {
			$diff[] = array($token, 1 /* ADDED */);
		}

		foreach ($end as $token) {
			$diff[] = array($token, 0 /* OLD */);
		}

		return $diff;
	}

	/**
	 * @param array $from
	 * @param array $to
	 *
	 * @return LongestCommonSubsequence
	 */
	private function selectLcsImplementation(array $from, array $to) {
		// We do not want to use the time-efficient implementation if its memory
		// footprint will probably exceed this value. Note that the footprint
		// calculation is only an estimation for the matrix and the LCS method
		// will typically allocate a bit more memory than this.
		$memoryLimit = 100 * 1024 * 1024;

		if ($this->calculateEstimatedFootprint($from, $to) > $memoryLimit) {
			return new MemoryEfficientImplementation;
		}

		return new TimeEfficientImplementation;
	}

	/**
	 * Calculates the estimated memory footprint for the DP-based method.
	 *
	 * @param array $from
	 * @param array $to
	 *
	 * @return int
	 */
	private function calculateEstimatedFootprint(array $from, array $to) {
		$itemSize = PHP_INT_SIZE == 4 ? 76 : 144;

		return $itemSize * pow(min(count($from), count($to)), 2);
	}
}
