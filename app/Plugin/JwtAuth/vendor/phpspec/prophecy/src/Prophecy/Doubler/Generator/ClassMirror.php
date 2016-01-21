<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prophecy\Doubler\Generator;

use Prophecy\Exception\Doubler\ClassMirrorException;
use Prophecy\Exception\InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Class mirror.
 * Core doubler class. Mirrors specific class and/or interfaces into class node tree.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class ClassMirror {
	private static $reflectableMethods = array(
			'__construct',
			'__destruct',
			'__sleep',
			'__wakeup',
			'__toString',
			'__call',
	);

	/**
	 * Reflects provided arguments into class node.
	 *
	 * @param ReflectionClass   $class
	 * @param ReflectionClass[] $interfaces
	 *
	 * @return Node\ClassNode
	 *
	 * @throws \Prophecy\Exception\InvalidArgumentException
	 */
	public function reflect(ReflectionClass $class = NULL, array $interfaces) {
		$node = new Node\ClassNode;

		if (NULL !== $class) {
			if (TRUE === $class->isInterface()) {
				throw new InvalidArgumentException(sprintf(
						"Could not reflect %s as a class, because it\n" .
						"is interface - use the second argument instead.",
						$class->getName()
				));
			}

			$this->reflectClassToNode($class, $node);
		}

		foreach ($interfaces as $interface) {
			if (!$interface instanceof ReflectionClass) {
				throw new InvalidArgumentException(sprintf(
						"[ReflectionClass \$interface1 [, ReflectionClass \$interface2]] array expected as\n" .
						"a second argument to `ClassMirror::reflect(...)`, but got %s.",
						is_object($interface) ? get_class($interface) . ' class' : gettype($interface)
				));
			}
			if (FALSE === $interface->isInterface()) {
				throw new InvalidArgumentException(sprintf(
						"Could not reflect %s as an interface, because it\n" .
						"is class - use the first argument instead.",
						$interface->getName()
				));
			}

			$this->reflectInterfaceToNode($interface, $node);
		}

		$node->addInterface('Prophecy\Doubler\Generator\ReflectionInterface');

		return $node;
	}

	private function reflectClassToNode(ReflectionClass $class, Node\ClassNode $node) {
		if (TRUE === $class->isFinal()) {
			throw new ClassMirrorException(sprintf(
					'Could not reflect class %s as it is marked final.', $class->getName()
			), $class);
		}

		$node->setParentClass($class->getName());

		foreach ($class->getMethods(ReflectionMethod::IS_ABSTRACT) as $method) {
			if (FALSE === $method->isProtected()) {
				continue;
			}

			$this->reflectMethodToNode($method, $node);
		}

		foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
			if (0 === strpos($method->getName(), '_')
					&& !in_array($method->getName(), self::$reflectableMethods)
			) {
				continue;
			}

			if (TRUE === $method->isFinal()) {
				continue;
			}

			$this->reflectMethodToNode($method, $node);
		}
	}

	private function reflectInterfaceToNode(ReflectionClass $interface, Node\ClassNode $node) {
		$node->addInterface($interface->getName());

		foreach ($interface->getMethods() as $method) {
			$this->reflectMethodToNode($method, $node);
		}
	}

	private function reflectMethodToNode(ReflectionMethod $method, Node\ClassNode $classNode) {
		$node = new Node\MethodNode($method->getName());

		if (TRUE === $method->isProtected()) {
			$node->setVisibility('protected');
		}

		if (TRUE === $method->isStatic()) {
			$node->setStatic();
		}

		if (TRUE === $method->returnsReference()) {
			$node->setReturnsReference();
		}

		if (version_compare(PHP_VERSION, '7.0', '>=') && TRUE === $method->hasReturnType()) {
			$node->setReturnType((string)$method->getReturnType());
		}

		if (is_array($params = $method->getParameters()) && count($params)) {
			foreach ($params as $param) {
				$this->reflectArgumentToNode($param, $node);
			}
		}

		$classNode->addMethod($node);
	}

	private function reflectArgumentToNode(ReflectionParameter $parameter, Node\MethodNode $methodNode) {
		$name = $parameter->getName() == '...' ? '__dot_dot_dot__' : $parameter->getName();
		$node = new Node\ArgumentNode($name);

		$typeHint = $this->getTypeHint($parameter);
		$node->setTypeHint($typeHint);

		if (TRUE === $parameter->isDefaultValueAvailable()) {
			$node->setDefault($parameter->getDefaultValue());
		} elseif (TRUE === $parameter->isOptional()
				|| (TRUE === $parameter->allowsNull() && $typeHint)
		) {
			$node->setDefault(NULL);
		}

		if (TRUE === $parameter->isPassedByReference()) {
			$node->setAsPassedByReference();
		}

		$methodNode->addArgument($node);
	}

	private function getTypeHint(ReflectionParameter $parameter) {
		if (NULL !== $className = $this->getParameterClassName($parameter)) {
			return $className;
		}

		if (TRUE === $parameter->isArray()) {
			return 'array';
		}

		if (version_compare(PHP_VERSION, '5.4', '>=') && TRUE === $parameter->isCallable()) {
			return 'callable';
		}

		if (version_compare(PHP_VERSION, '7.0', '>=') && TRUE === $parameter->hasType()) {
			return (string)$parameter->getType();
		}

		return NULL;
	}

	private function getParameterClassName(ReflectionParameter $parameter) {
		try {
			return $parameter->getClass() ? $parameter->getClass()->getName() : NULL;
		} catch (\ReflectionException $e) {
			preg_match('/\[\s\<\w+?>\s([\w,\\\]+)/s', $parameter, $matches);

			return isset($matches[1]) ? $matches[1] : NULL;
		}
	}
}
