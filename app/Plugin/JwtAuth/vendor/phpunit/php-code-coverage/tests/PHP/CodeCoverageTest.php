<?php
/*
 * This file is part of the PHP_CodeCoverage package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!defined('TEST_FILES_PATH')) {
	define(
	'TEST_FILES_PATH',
			dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR .
			'_files' . DIRECTORY_SEPARATOR
	);
}

require_once TEST_FILES_PATH . '../TestCase.php';
require_once TEST_FILES_PATH . 'BankAccount.php';
require_once TEST_FILES_PATH . 'BankAccountTest.php';

/**
 * Tests for the PHP_CodeCoverage class.
 *
 * @since Class available since Release 1.0.0
 */
class PHP_CodeCoverageTest extends PHP_CodeCoverage_TestCase {
	/**
	 * @var PHP_CodeCoverage
	 */
	private $coverage;

	/**
	 * @covers PHP_CodeCoverage::__construct
	 * @covers PHP_CodeCoverage::filter
	 */
	public function testConstructor() {
		$this->assertAttributeInstanceOf(
				'PHP_CodeCoverage_Driver_Xdebug',
				'driver',
				$this->coverage
		);

		$this->assertAttributeInstanceOf(
				'PHP_CodeCoverage_Filter',
				'filter',
				$this->coverage
		);
	}

	/**
	 * @covers PHP_CodeCoverage::__construct
	 * @covers PHP_CodeCoverage::filter
	 */
	public function testConstructor2() {
		$filter = new PHP_CodeCoverage_Filter;
		$coverage = new PHP_CodeCoverage(NULL, $filter);

		$this->assertAttributeInstanceOf(
				'PHP_CodeCoverage_Driver_Xdebug',
				'driver',
				$coverage
		);

		$this->assertSame($filter, $coverage->filter());
	}

	/**
	 * @covers            PHP_CodeCoverage::start
	 * @expectedException PHP_CodeCoverage_Exception
	 */
	public function testStartThrowsExceptionForInvalidArgument() {
		$this->coverage->start(NULL, array(), NULL);
	}

	/**
	 * @covers            PHP_CodeCoverage::stop
	 * @expectedException PHP_CodeCoverage_Exception
	 */
	public function testStopThrowsExceptionForInvalidArgument() {
		$this->coverage->stop(NULL);
	}

	/**
	 * @covers            PHP_CodeCoverage::stop
	 * @expectedException PHP_CodeCoverage_Exception
	 */
	public function testStopThrowsExceptionForInvalidArgument2() {
		$this->coverage->stop(TRUE, NULL);
	}

	/**
	 * @covers            PHP_CodeCoverage::append
	 * @expectedException PHP_CodeCoverage_Exception
	 */
	public function testAppendThrowsExceptionForInvalidArgument() {
		$this->coverage->append(array(), NULL);
	}

	/**
	 * @covers            PHP_CodeCoverage::setCacheTokens
	 * @expectedException PHP_CodeCoverage_Exception
	 */
	public function testSetCacheTokensThrowsExceptionForInvalidArgument() {
		$this->coverage->setCacheTokens(NULL);
	}

	/**
	 * @covers PHP_CodeCoverage::setCacheTokens
	 */
	public function testSetCacheTokens() {
		$this->coverage->setCacheTokens(TRUE);
		$this->assertAttributeEquals(TRUE, 'cacheTokens', $this->coverage);
	}

	/**
	 * @covers            PHP_CodeCoverage::setCheckForUnintentionallyCoveredCode
	 * @expectedException PHP_CodeCoverage_Exception
	 */
	public function testSetCheckForUnintentionallyCoveredCodeThrowsExceptionForInvalidArgument() {
		$this->coverage->setCheckForUnintentionallyCoveredCode(NULL);
	}

	/**
	 * @covers PHP_CodeCoverage::setCheckForUnintentionallyCoveredCode
	 */
	public function testSetCheckForUnintentionallyCoveredCode() {
		$this->coverage->setCheckForUnintentionallyCoveredCode(TRUE);
		$this->assertAttributeEquals(
				TRUE,
				'checkForUnintentionallyCoveredCode',
				$this->coverage
		);
	}

	/**
	 * @covers            PHP_CodeCoverage::setForceCoversAnnotation
	 * @expectedException PHP_CodeCoverage_Exception
	 */
	public function testSetForceCoversAnnotationThrowsExceptionForInvalidArgument() {
		$this->coverage->setForceCoversAnnotation(NULL);
	}

	/**
	 * @covers PHP_CodeCoverage::setForceCoversAnnotation
	 */
	public function testSetForceCoversAnnotation() {
		$this->coverage->setForceCoversAnnotation(TRUE);
		$this->assertAttributeEquals(
				TRUE,
				'forceCoversAnnotation',
				$this->coverage
		);
	}

	/**
	 * @covers            PHP_CodeCoverage::setAddUncoveredFilesFromWhitelist
	 * @expectedException PHP_CodeCoverage_Exception
	 */
	public function testSetAddUncoveredFilesFromWhitelistThrowsExceptionForInvalidArgument() {
		$this->coverage->setAddUncoveredFilesFromWhitelist(NULL);
	}

	/**
	 * @covers PHP_CodeCoverage::setAddUncoveredFilesFromWhitelist
	 */
	public function testSetAddUncoveredFilesFromWhitelist() {
		$this->coverage->setAddUncoveredFilesFromWhitelist(TRUE);
		$this->assertAttributeEquals(
				TRUE,
				'addUncoveredFilesFromWhitelist',
				$this->coverage
		);
	}

	/**
	 * @covers            PHP_CodeCoverage::setProcessUncoveredFilesFromWhitelist
	 * @expectedException PHP_CodeCoverage_Exception
	 */
	public function testSetProcessUncoveredFilesFromWhitelistThrowsExceptionForInvalidArgument() {
		$this->coverage->setProcessUncoveredFilesFromWhitelist(NULL);
	}

	/**
	 * @covers PHP_CodeCoverage::setProcessUncoveredFilesFromWhitelist
	 */
	public function testSetProcessUncoveredFilesFromWhitelist() {
		$this->coverage->setProcessUncoveredFilesFromWhitelist(TRUE);
		$this->assertAttributeEquals(
				TRUE,
				'processUncoveredFilesFromWhitelist',
				$this->coverage
		);
	}

	/**
	 * @covers PHP_CodeCoverage::setMapTestClassNameToCoveredClassName
	 */
	public function testSetMapTestClassNameToCoveredClassName() {
		$this->coverage->setMapTestClassNameToCoveredClassName(TRUE);
		$this->assertAttributeEquals(
				TRUE,
				'mapTestClassNameToCoveredClassName',
				$this->coverage
		);
	}

	/**
	 * @covers            PHP_CodeCoverage::setMapTestClassNameToCoveredClassName
	 * @expectedException PHP_CodeCoverage_Exception
	 */
	public function testSetMapTestClassNameToCoveredClassNameThrowsExceptionForInvalidArgument() {
		$this->coverage->setMapTestClassNameToCoveredClassName(NULL);
	}

	/**
	 * @covers PHP_CodeCoverage::clear
	 */
	public function testClear() {
		$this->coverage->clear();

		$this->assertAttributeEquals(NULL, 'currentId', $this->coverage);
		$this->assertAttributeEquals(array(), 'data', $this->coverage);
		$this->assertAttributeEquals(array(), 'tests', $this->coverage);
	}

	/**
	 * @covers PHP_CodeCoverage::start
	 * @covers PHP_CodeCoverage::stop
	 * @covers PHP_CodeCoverage::append
	 * @covers PHP_CodeCoverage::applyListsFilter
	 * @covers PHP_CodeCoverage::initializeFilesThatAreSeenTheFirstTime
	 * @covers PHP_CodeCoverage::applyCoversAnnotationFilter
	 * @covers PHP_CodeCoverage::getTests
	 */
	public function testCollect() {
		$coverage = $this->getCoverageForBankAccount();

		$this->assertEquals(
				$this->getExpectedDataArrayForBankAccount(),
				$coverage->getData()
		);

		if (version_compare(PHPUnit_Runner_Version::id(), '4.7', '>=')) {
			$size = 'unknown';
		} else {
			$size = 'small';
		}

		$this->assertEquals(
				array(
						'BankAccountTest::testBalanceIsInitiallyZero' => array('size' => $size, 'status' => NULL),
						'BankAccountTest::testBalanceCannotBecomeNegative' => array('size' => $size, 'status' => NULL),
						'BankAccountTest::testBalanceCannotBecomeNegative2' => array('size' => $size, 'status' => NULL),
						'BankAccountTest::testDepositWithdrawMoney' => array('size' => $size, 'status' => NULL)
				),
				$coverage->getTests()
		);
	}

	/**
	 * @covers PHP_CodeCoverage::getData
	 * @covers PHP_CodeCoverage::merge
	 */
	public function testMerge() {
		$coverage = $this->getCoverageForBankAccountForFirstTwoTests();
		$coverage->merge($this->getCoverageForBankAccountForLastTwoTests());

		$this->assertEquals(
				$this->getExpectedDataArrayForBankAccount(),
				$coverage->getData()
		);
	}

	/**
	 * @covers PHP_CodeCoverage::getData
	 * @covers PHP_CodeCoverage::merge
	 */
	public function testMerge2() {
		$coverage = new PHP_CodeCoverage(
				$this->getMock('PHP_CodeCoverage_Driver_Xdebug'),
				new PHP_CodeCoverage_Filter
		);

		$coverage->merge($this->getCoverageForBankAccount());

		$this->assertEquals(
				$this->getExpectedDataArrayForBankAccount(),
				$coverage->getData()
		);
	}

	/**
	 * @covers PHP_CodeCoverage::getLinesToBeIgnored
	 */
	public function testGetLinesToBeIgnored() {
		$this->assertEquals(
				array(
						1,
						3,
						4,
						5,
						7,
						8,
						9,
						10,
						11,
						12,
						13,
						14,
						15,
						16,
						17,
						18,
						19,
						20,
						21,
						22,
						23,
						24,
						25,
						26,
						27,
						28,
						30,
						32,
						33,
						34,
						35,
						36,
						37,
						38
				),
				$this->getLinesToBeIgnored()->invoke(
						$this->coverage,
						TEST_FILES_PATH . 'source_with_ignore.php'
				)
		);
	}

	/**
	 * @return ReflectionMethod
	 */
	private function getLinesToBeIgnored() {
		$getLinesToBeIgnored = new ReflectionMethod(
				'PHP_CodeCoverage',
				'getLinesToBeIgnored'
		);

		$getLinesToBeIgnored->setAccessible(TRUE);

		return $getLinesToBeIgnored;
	}

	/**
	 * @covers PHP_CodeCoverage::getLinesToBeIgnored
	 */
	public function testGetLinesToBeIgnored2() {
		$this->assertEquals(
				array(1, 5),
				$this->getLinesToBeIgnored()->invoke(
						$this->coverage,
						TEST_FILES_PATH . 'source_without_ignore.php'
				)
		);
	}

	/**
	 * @covers PHP_CodeCoverage::getLinesToBeIgnored
	 */
	public function testGetLinesToBeIgnored3() {
		$this->assertEquals(
				array(
						1,
						2,
						3,
						4,
						5,
						8,
						11,
						15,
						16,
						19,
						20
				),
				$this->getLinesToBeIgnored()->invoke(
						$this->coverage,
						TEST_FILES_PATH . 'source_with_class_and_anonymous_function.php'
				)
		);
	}

	/**
	 * @covers PHP_CodeCoverage::getLinesToBeIgnored
	 */
	public function testGetLinesToBeIgnoredOneLineAnnotations() {
		$this->assertEquals(
				array(
						1,
						2,
						3,
						4,
						5,
						6,
						7,
						8,
						9,
						10,
						11,
						12,
						13,
						14,
						15,
						16,
						18,
						20,
						21,
						23,
						24,
						25,
						27,
						28,
						29,
						30,
						31,
						32,
						33,
						34,
						37
				),
				$this->getLinesToBeIgnored()->invoke(
						$this->coverage,
						TEST_FILES_PATH . 'source_with_oneline_annotations.php'
				)
		);
	}

	/**
	 * @covers PHP_CodeCoverage::getLinesToBeIgnored
	 */
	public function testGetLinesToBeIgnoredWhenIgnoreIsDisabled() {
		$this->coverage->setDisableIgnoredLines(TRUE);

		$this->assertEquals(
				array(),
				$this->getLinesToBeIgnored()->invoke(
						$this->coverage,
						TEST_FILES_PATH . 'source_with_ignore.php'
				)
		);
	}

	protected function setUp() {
		$this->coverage = new PHP_CodeCoverage;
	}
}
