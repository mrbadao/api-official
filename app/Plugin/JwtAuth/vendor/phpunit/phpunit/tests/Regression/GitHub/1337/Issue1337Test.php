<?php

	class Issue1337Test extends PHPUnit_Framework_TestCase {
		/**
		 * @dataProvider dataProvider
		 */
		public function testProvider($a) {
			$this->assertTrue($a);
		}

		public function dataProvider() {
			return array(
					'c:\\' => array(TRUE),
					0.9 => array(TRUE)
			);
		}
	}
