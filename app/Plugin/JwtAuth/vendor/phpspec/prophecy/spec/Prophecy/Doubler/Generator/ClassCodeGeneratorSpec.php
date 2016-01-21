<?php

	namespace spec\Prophecy\Doubler\Generator;

	use PhpSpec\ObjectBehavior;

	class ClassCodeGeneratorSpec extends ObjectBehavior {
		/**
		 * @param \Prophecy\Doubler\Generator\Node\ClassNode    $class
		 * @param \Prophecy\Doubler\Generator\Node\MethodNode   $method1
		 * @param \Prophecy\Doubler\Generator\Node\MethodNode   $method2
		 * @param \Prophecy\Doubler\Generator\Node\MethodNode   $method3
		 * @param \Prophecy\Doubler\Generator\Node\ArgumentNode $argument11
		 * @param \Prophecy\Doubler\Generator\Node\ArgumentNode $argument12
		 * @param \Prophecy\Doubler\Generator\Node\ArgumentNode $argument21
		 * @param \Prophecy\Doubler\Generator\Node\ArgumentNode $argument31
		 */
		function it_generates_proper_php_code_for_specific_ClassNode(
				$class, $method1, $method2, $method3, $argument11, $argument12, $argument21, $argument31
		) {
			$class->getParentClass()->willReturn('RuntimeException');
			$class->getInterfaces()->willReturn(array(
					'Prophecy\Doubler\Generator\MirroredInterface', 'ArrayAccess', 'ArrayIterator'
			));
			$class->getProperties()->willReturn(array('name' => 'public', 'email' => 'private'));
			$class->getMethods()->willReturn(array($method1, $method2, $method3));

			$method1->getName()->willReturn('getName');
			$method1->getVisibility()->willReturn('public');
			$method1->returnsReference()->willReturn(FALSE);
			$method1->isStatic()->willReturn(TRUE);
			$method1->getArguments()->willReturn(array($argument11, $argument12));
			$method1->hasReturnType()->willReturn(TRUE);
			$method1->getReturnType()->willReturn('string');
			$method1->getCode()->willReturn('return $this->name;');

			$method2->getName()->willReturn('getEmail');
			$method2->getVisibility()->willReturn('protected');
			$method2->returnsReference()->willReturn(FALSE);
			$method2->isStatic()->willReturn(FALSE);
			$method2->getArguments()->willReturn(array($argument21));
			$method2->hasReturnType()->willReturn(FALSE);
			$method2->getCode()->willReturn('return $this->email;');

			$method3->getName()->willReturn('getRefValue');
			$method3->getVisibility()->willReturn('public');
			$method3->returnsReference()->willReturn(TRUE);
			$method3->isStatic()->willReturn(FALSE);
			$method3->getArguments()->willReturn(array($argument31));
			$method3->hasReturnType()->willReturn(FALSE);
			$method3->getCode()->willReturn('return $this->refValue;');

			$argument11->getName()->willReturn('fullname');
			$argument11->getTypeHint()->willReturn('array');
			$argument11->isOptional()->willReturn(TRUE);
			$argument11->getDefault()->willReturn(NULL);
			$argument11->isPassedByReference()->willReturn(FALSE);

			$argument12->getName()->willReturn('class');
			$argument12->getTypeHint()->willReturn('ReflectionClass');
			$argument12->isOptional()->willReturn(FALSE);
			$argument12->isPassedByReference()->willReturn(FALSE);

			$argument21->getName()->willReturn('default');
			$argument21->getTypeHint()->willReturn(NULL);
			$argument21->isOptional()->willReturn(TRUE);
			$argument21->getDefault()->willReturn('ever.zet@gmail.com');
			$argument21->isPassedByReference()->willReturn(FALSE);

			$argument31->getName()->willReturn('refValue');
			$argument31->getTypeHint()->willReturn(NULL);
			$argument31->isOptional()->willReturn(FALSE);
			$argument31->getDefault()->willReturn();
			$argument31->isPassedByReference()->willReturn(FALSE);

			$code = $this->generate('CustomClass', $class);
			$expected = <<<'PHP'
namespace  {
class CustomClass extends \RuntimeException implements \Prophecy\Doubler\Generator\MirroredInterface, \ArrayAccess, \ArrayIterator {
public $name;
private $email;

public static function getName(array $fullname = NULL, \ReflectionClass $class): string {
return $this->name;
}
protected  function getEmail( $default = 'ever.zet@gmail.com') {
return $this->email;
}
public  function &getRefValue( $refValue) {
return $this->refValue;
}

}
}
PHP;
			$expected = strtr($expected, array("\r\n" => "\n", "\r" => "\n"));
			$code->shouldBe($expected);
		}

		/**
		 * @param \Prophecy\Doubler\Generator\Node\ClassNode    $class
		 * @param \Prophecy\Doubler\Generator\Node\MethodNode   $method
		 * @param \Prophecy\Doubler\Generator\Node\ArgumentNode $argument
		 */
		function it_overrides_properly_methods_with_args_passed_by_reference(
				$class, $method, $argument
		) {
			$class->getParentClass()->willReturn('RuntimeException');
			$class->getInterfaces()->willReturn(array('Prophecy\Doubler\Generator\MirroredInterface'));
			$class->getProperties()->willReturn(array());
			$class->getMethods()->willReturn(array($method));

			$method->getName()->willReturn('getName');
			$method->getVisibility()->willReturn('public');
			$method->isStatic()->willReturn(FALSE);
			$method->getArguments()->willReturn(array($argument));
			$method->hasReturnType()->willReturn(FALSE);
			$method->returnsReference()->willReturn(FALSE);
			$method->getCode()->willReturn('return $this->name;');

			$argument->getName()->willReturn('fullname');
			$argument->getTypeHint()->willReturn('array');
			$argument->isOptional()->willReturn(TRUE);
			$argument->getDefault()->willReturn(NULL);
			$argument->isPassedByReference()->willReturn(TRUE);

			$code = $this->generate('CustomClass', $class);
			$expected = <<<'PHP'
namespace  {
class CustomClass extends \RuntimeException implements \Prophecy\Doubler\Generator\MirroredInterface {

public  function getName(array &$fullname = NULL) {
return $this->name;
}

}
}
PHP;
			$expected = strtr($expected, array("\r\n" => "\n", "\r" => "\n"));
			$code->shouldBe($expected);
		}

		/**
		 * @param \Prophecy\Doubler\Generator\Node\ClassNode $class
		 */
		function it_generates_empty_class_for_empty_ClassNode($class) {
			$class->getParentClass()->willReturn('stdClass');
			$class->getInterfaces()->willReturn(array('Prophecy\Doubler\Generator\MirroredInterface'));
			$class->getProperties()->willReturn(array());
			$class->getMethods()->willReturn(array());

			$code = $this->generate('CustomClass', $class);
			$expected = <<<'PHP'
namespace  {
class CustomClass extends \stdClass implements \Prophecy\Doubler\Generator\MirroredInterface {


}
}
PHP;
			$expected = strtr($expected, array("\r\n" => "\n", "\r" => "\n"));
			$code->shouldBe($expected);
		}

		/**
		 * @param \Prophecy\Doubler\Generator\Node\ClassNode $class
		 */
		function it_wraps_class_in_namespace_if_it_is_namespaced($class) {
			$class->getParentClass()->willReturn('stdClass');
			$class->getInterfaces()->willReturn(array('Prophecy\Doubler\Generator\MirroredInterface'));
			$class->getProperties()->willReturn(array());
			$class->getMethods()->willReturn(array());

			$code = $this->generate('My\Awesome\CustomClass', $class);
			$expected = <<<'PHP'
namespace My\Awesome {
class CustomClass extends \stdClass implements \Prophecy\Doubler\Generator\MirroredInterface {


}
}
PHP;
			$expected = strtr($expected, array("\r\n" => "\n", "\r" => "\n"));
			$code->shouldBe($expected);
		}
	}
