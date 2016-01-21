<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prophecy\Doubler\Generator\Node;

/**
 * Argument node.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class ArgumentNode {
	private $name;
	private $typeHint;
	private $default;
	private $optional = FALSE;
	private $byReference = FALSE;

	/**
	 * @param string $name
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function getTypeHint() {
		return $this->typeHint;
	}

	public function setTypeHint($typeHint = NULL) {
		$this->typeHint = $typeHint;
	}

	public function getDefault() {
		return $this->default;
	}

	public function setDefault($default = NULL) {
		$this->optional = TRUE;
		$this->default = $default;
	}

	public function isOptional() {
		return $this->optional;
	}

	public function setAsPassedByReference($byReference = TRUE) {
		$this->byReference = $byReference;
	}

	public function isPassedByReference() {
		return $this->byReference;
	}
}
