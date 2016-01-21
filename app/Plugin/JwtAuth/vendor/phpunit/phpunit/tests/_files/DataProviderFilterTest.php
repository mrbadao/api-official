<?php

class DataProviderFilterTest extends PHPUnit_Framework_TestCase {
	public static function truthProvider() {
		return array(
				array(TRUE),
				array(TRUE),
				array(TRUE),
				array(TRUE)
		);
	}

	public static function falseProvider() {
		return array(
				'false test' => array(FALSE),
				'false test 2' => array(FALSE),
				'other false test' => array(FALSE),
				'other false test2' => array(FALSE)
		);
	}

	/**
	 * @dataProvider truthProvider
	 */
	public function testTrue($truth) {
		$this->assertTrue($truth);
	}

	/**
	 * @dataProvider falseProvider
	 */
	public function testFalse($false) {
		$this->assertFalse($false);
	}
}
