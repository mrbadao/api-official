<?php

	namespace spec\Prophecy\Comparator;

	use PhpSpec\ObjectBehavior;
	use Prophecy\Argument;

	class ClosureComparatorSpec extends ObjectBehavior {
		function it_is_comparator() {
			$this->shouldHaveType('SebastianBergmann\Comparator\Comparator');
		}

		function it_accepts_only_closures() {
			$this->accepts(123, 321)->shouldReturn(FALSE);
			$this->accepts('string', 'string')->shouldReturn(FALSE);
			$this->accepts(FALSE, TRUE)->shouldReturn(FALSE);
			$this->accepts(TRUE, FALSE)->shouldReturn(FALSE);
			$this->accepts((object)array(), (object)array())->shouldReturn(FALSE);
			$this->accepts(function () {
			}, (object)array())->shouldReturn(FALSE);
			$this->accepts(function () {
			}, (object)array())->shouldReturn(FALSE);

			$this->accepts(function () {
			}, function () {
			})->shouldReturn(TRUE);
		}

		function it_asserts_that_all_closures_are_different() {
			$this->shouldThrow()->duringAssertEquals(function () {
			}, function () {
			});
		}

		function it_asserts_that_all_closures_are_different_even_if_its_the_same_closure() {
			$closure = function () {
			};

			$this->shouldThrow()->duringAssertEquals($closure, $closure);
		}
	}
