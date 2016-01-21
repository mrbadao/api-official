<?php

	class Singleton {
		private static $uniqueInstance = NULL;

		protected function __construct() {
		}

		public static function getInstance() {
			if (self::$uniqueInstance === NULL) {
				self::$uniqueInstance = new self;
			}

			return self::$uniqueInstance;
		}

		final private function __clone() {
		}
	}
