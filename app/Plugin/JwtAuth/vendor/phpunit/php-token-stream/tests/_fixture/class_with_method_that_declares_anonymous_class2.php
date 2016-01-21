<?php

	class Test {
		public function methodOne() {
			$foo = new class {
				public function method_in_anonymous_class() {
					return TRUE;
				}
			};

			return $foo->method_in_anonymous_class();
		}

		public function methodTwo() {
			return FALSE;
		}
	}
