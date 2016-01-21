<?php
	/*
	 * This file is part of PHPUnit.
	 *
	 * (c) Sebastian Bergmann <sebastian@phpunit.de>
	 *
	 * For the full copyright and license information, please view the LICENSE
	 * file that was distributed with this source code.
	 */

	/**
	 * Factory for PHPUnit_Framework_Exception objects that are used to describe
	 * invalid arguments passed to a function or method.
	 *
	 * @since Class available since Release 3.4.0
	 */
	class PHPUnit_Util_InvalidArgumentHelper {
		/**
		 * @param int    $argument
		 * @param string $type
		 * @param mixed  $value
		 *
		 * @return PHPUnit_Framework_Exception
		 */
		public static function factory($argument, $type, $value = NULL) {
			$stack = debug_backtrace(FALSE);

			return new PHPUnit_Framework_Exception(
					sprintf(
							'Argument #%d%sof %s::%s() must be a %s',
							$argument,
							$value !== NULL ? ' (' . gettype($value) . '#' . $value . ')' : ' (No Value) ',
							$stack[1]['class'],
							$stack[1]['function'],
							$type
					)
			);
		}
	}
