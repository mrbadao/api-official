<?php
/**
 * Test Shell
 *
 * This Shell allows the running of test suites via the cake command line
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://book.cakephp.org/2.0/en/development/testing.html
 * @since         CakePHP(tm) v 1.2.0.4433
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Shell', 'Console');
App::uses('CakeTestSuiteDispatcher', 'TestSuite');
App::uses('CakeTestSuiteCommand', 'TestSuite');
App::uses('CakeTestLoader', 'TestSuite');

/**
 * Provides a CakePHP wrapper around PHPUnit.
 * Adds in CakePHP's fixtures and gives access to plugin, app and core test cases
 *
 * @package       Cake.Console.Command
 */
class TestShell extends Shell {

	/**
	 * Dispatcher object for the run.
	 *
	 * @var CakeTestDispatcher
	 */
	protected $_dispatcher = NULL;

	/**
	 * Gets the option parser instance and configures it.
	 *
	 * @return ConsoleOptionParser
	 */
	public function getOptionParser() {
		$parser = new ConsoleOptionParser($this->name);

		$parser->description(
				__d('cake_console', 'The CakePHP Testsuite allows you to run test cases from the command line')
		)->addArgument('category', array(
				'help' => __d('cake_console', 'The category for the test, or test file, to test.'),
				'required' => FALSE
		))->addArgument('file', array(
				'help' => __d('cake_console', 'The path to the file, or test file, to test.'),
				'required' => FALSE
		))->addOption('log-junit', array(
				'help' => __d('cake_console', '<file> Log test execution in JUnit XML format to file.'),
				'default' => FALSE
		))->addOption('log-json', array(
				'help' => __d('cake_console', '<file> Log test execution in JSON format to file.'),
				'default' => FALSE
		))->addOption('log-tap', array(
				'help' => __d('cake_console', '<file> Log test execution in TAP format to file.'),
				'default' => FALSE
		))->addOption('log-dbus', array(
				'help' => __d('cake_console', 'Log test execution to DBUS.'),
				'default' => FALSE
		))->addOption('coverage-html', array(
				'help' => __d('cake_console', '<dir> Generate code coverage report in HTML format.'),
				'default' => FALSE
		))->addOption('coverage-clover', array(
				'help' => __d('cake_console', '<file> Write code coverage data in Clover XML format.'),
				'default' => FALSE
		))->addOption('testdox-html', array(
				'help' => __d('cake_console', '<file> Write agile documentation in HTML format to file.'),
				'default' => FALSE
		))->addOption('testdox-text', array(
				'help' => __d('cake_console', '<file> Write agile documentation in Text format to file.'),
				'default' => FALSE
		))->addOption('filter', array(
				'help' => __d('cake_console', '<pattern> Filter which tests to run.'),
				'default' => FALSE
		))->addOption('group', array(
				'help' => __d('cake_console', '<name> Only runs tests from the specified group(s).'),
				'default' => FALSE
		))->addOption('exclude-group', array(
				'help' => __d('cake_console', '<name> Exclude tests from the specified group(s).'),
				'default' => FALSE
		))->addOption('list-groups', array(
				'help' => __d('cake_console', 'List available test groups.'),
				'boolean' => TRUE
		))->addOption('loader', array(
				'help' => __d('cake_console', 'TestSuiteLoader implementation to use.'),
				'default' => FALSE
		))->addOption('repeat', array(
				'help' => __d('cake_console', '<times> Runs the test(s) repeatedly.'),
				'default' => FALSE
		))->addOption('tap', array(
				'help' => __d('cake_console', 'Report test execution progress in TAP format.'),
				'boolean' => TRUE
		))->addOption('testdox', array(
				'help' => __d('cake_console', 'Report test execution progress in TestDox format.'),
				'default' => FALSE,
				'boolean' => TRUE
		))->addOption('no-colors', array(
				'help' => __d('cake_console', 'Do not use colors in output.'),
				'boolean' => TRUE
		))->addOption('stderr', array(
				'help' => __d('cake_console', 'Write to STDERR instead of STDOUT.'),
				'boolean' => TRUE
		))->addOption('stop-on-error', array(
				'help' => __d('cake_console', 'Stop execution upon first error or failure.'),
				'boolean' => TRUE
		))->addOption('stop-on-failure', array(
				'help' => __d('cake_console', 'Stop execution upon first failure.'),
				'boolean' => TRUE
		))->addOption('stop-on-skipped', array(
				'help' => __d('cake_console', 'Stop execution upon first skipped test.'),
				'boolean' => TRUE
		))->addOption('stop-on-incomplete', array(
				'help' => __d('cake_console', 'Stop execution upon first incomplete test.'),
				'boolean' => TRUE
		))->addOption('strict', array(
				'help' => __d('cake_console', 'Mark a test as incomplete if no assertions are made.'),
				'boolean' => TRUE
		))->addOption('wait', array(
				'help' => __d('cake_console', 'Waits for a keystroke after each test.'),
				'boolean' => TRUE
		))->addOption('process-isolation', array(
				'help' => __d('cake_console', 'Run each test in a separate PHP process.'),
				'boolean' => TRUE
		))->addOption('no-globals-backup', array(
				'help' => __d('cake_console', 'Do not backup and restore $GLOBALS for each test.'),
				'boolean' => TRUE
		))->addOption('static-backup', array(
				'help' => __d('cake_console', 'Backup and restore static attributes for each test.'),
				'boolean' => TRUE
		))->addOption('syntax-check', array(
				'help' => __d('cake_console', 'Try to check source files for syntax errors.'),
				'boolean' => TRUE
		))->addOption('bootstrap', array(
				'help' => __d('cake_console', '<file> A "bootstrap" PHP file that is run before the tests.'),
				'default' => FALSE
		))->addOption('configuration', array(
				'help' => __d('cake_console', '<file> Read configuration from XML file.'),
				'default' => FALSE
		))->addOption('no-configuration', array(
				'help' => __d('cake_console', 'Ignore default configuration file (phpunit.xml).'),
				'boolean' => TRUE
		))->addOption('include-path', array(
				'help' => __d('cake_console', '<path(s)> Prepend PHP include_path with given path(s).'),
				'default' => FALSE
		))->addOption('directive', array(
				'help' => __d('cake_console', 'key[=value] Sets a php.ini value.'),
				'default' => FALSE
		))->addOption('fixture', array(
				'help' => __d('cake_console', 'Choose a custom fixture manager.')
		))->addOption('debug', array(
				'help' => __d('cake_console', 'More verbose output.')
		));

		return $parser;
	}

	/**
	 * Initialization method installs PHPUnit and loads all plugins
	 *
	 * @return void
	 * @throws Exception
	 */
	public function initialize() {
		$this->_dispatcher = new CakeTestSuiteDispatcher();
		$success = $this->_dispatcher->loadTestFramework();
		if (!$success) {
			throw new Exception(__d('cake_dev', 'Please install PHPUnit framework v3.7 <info>(http://www.phpunit.de)</info>'));
		}
	}

	/**
	 * Main entry point to this shell
	 *
	 * @return void
	 */
	public function main() {
		$this->out(__d('cake_console', 'CakePHP Test Shell'));
		$this->hr();

		$args = $this->_parseArgs();

		if (empty($args['case'])) {
			return $this->available();
		}

		$this->_run($args, $this->_runnerOptions());
	}

	/**
	 * Parse the CLI options into an array CakeTestDispatcher can use.
	 *
	 * @return array|null Array of params for CakeTestDispatcher or null.
	 */
	protected function _parseArgs() {
		if (empty($this->args)) {
			return NULL;
		}
		$params = array(
				'core' => FALSE,
				'app' => FALSE,
				'plugin' => NULL,
				'output' => 'text',
		);

		if (strpos($this->args[0], '.php')) {
			$category = $this->_mapFileToCategory($this->args[0]);
			$params['case'] = $this->_mapFileToCase($this->args[0], $category);
		} else {
			$category = $this->args[0];
			if (isset($this->args[1])) {
				$params['case'] = $this->args[1];
			}
		}

		if ($category === 'core') {
			$params['core'] = TRUE;
		} elseif ($category === 'app') {
			$params['app'] = TRUE;
		} else {
			$params['plugin'] = $category;
		}

		return $params;
	}

	/**
	 * For the given file, what category of test is it? returns app, core or the name of the plugin
	 *
	 * @param string $file The file to map.
	 *
	 * @return string
	 */
	protected function _mapFileToCategory($file) {
		$_file = realpath($file);
		if ($_file) {
			$file = $_file;
		}

		$file = str_replace(DS, '/', $file);
		if (strpos($file, 'lib/Cake/') !== FALSE) {
			return 'core';
		} elseif (preg_match('@(?:plugins|Plugin)/([^/]*)@', $file, $match)) {
			return $match[1];
		}

		return 'app';
	}

	/**
	 * Find the test case for the passed file. The file could itself be a test.
	 *
	 * @param string $file               The file to map.
	 * @param string $category           The test file category.
	 * @param bool   $throwOnMissingFile Whether or not to throw an exception.
	 *
	 * @return array array(type, case)
	 * @throws Exception
	 */
	protected function _mapFileToCase($file, $category, $throwOnMissingFile = TRUE) {
		if (!$category || (substr($file, -4) !== '.php')) {
			return FALSE;
		}

		$_file = realpath($file);
		if ($_file) {
			$file = $_file;
		}

		$testFile = $testCase = NULL;

		if (preg_match('@Test[\\\/]@', $file)) {

			if (substr($file, -8) === 'Test.php') {

				$testCase = substr($file, 0, -8);
				$testCase = str_replace(DS, '/', $testCase);

				if ($testCase = preg_replace('@.*Test\/Case\/@', '', $testCase)) {

					if ($category === 'core') {
						$testCase = str_replace('lib/Cake', '', $testCase);
					}

					return $testCase;
				}

				throw new Exception(__d('cake_dev', 'Test case %s cannot be run via this shell', $testFile));
			}
		}

		$file = substr($file, 0, -4);
		if ($category === 'core') {

			$testCase = str_replace(DS, '/', $file);
			$testCase = preg_replace('@.*lib/Cake/@', '', $file);
			$testCase[0] = strtoupper($testCase[0]);
			$testFile = CAKE . 'Test/Case/' . $testCase . 'Test.php';

			if (!file_exists($testFile) && $throwOnMissingFile) {
				throw new Exception(__d('cake_dev', 'Test case %s not found', $testFile));
			}

			return $testCase;
		}

		if ($category === 'app') {
			$testFile = str_replace(APP, APP . 'Test/Case/', $file) . 'Test.php';
		} else {
			$testFile = preg_replace(
					"@((?:plugins|Plugin)[\\/]{$category}[\\/])(.*)$@",
					'\1Test/Case/\2Test.php',
					$file
			);
		}

		if (!file_exists($testFile) && $throwOnMissingFile) {
			throw new Exception(__d('cake_dev', 'Test case %s not found', $testFile));
		}

		$testCase = substr($testFile, 0, -8);
		$testCase = str_replace(DS, '/', $testCase);
		$testCase = preg_replace('@.*Test/Case/@', '', $testCase);

		return $testCase;
	}

	/**
	 * Shows a list of available test cases and gives the option to run one of them
	 *
	 * @return void
	 */
	public function available() {
		$params = $this->_parseArgs();
		$testCases = CakeTestLoader::generateTestList($params);
		$app = $params['app'];
		$plugin = $params['plugin'];

		$title = "Core Test Cases:";
		$category = 'core';
		if ($app) {
			$title = "App Test Cases:";
			$category = 'app';
		} elseif ($plugin) {
			$title = Inflector::humanize($plugin) . " Test Cases:";
			$category = $plugin;
		}

		if (empty($testCases)) {
			$this->out(__d('cake_console', "No test cases available \n\n"));

			return $this->out($this->OptionParser->help());
		}

		$this->out($title);
		$i = 1;
		$cases = array();
		foreach ($testCases as $testCase) {
			$case = str_replace('Test.php', '', $testCase);
			$this->out("[$i] $case");
			$cases[$i] = $case;
			$i++;
		}

		while ($choice = $this->in(__d('cake_console', 'What test case would you like to run?'), NULL, 'q')) {
			if (is_numeric($choice) && isset($cases[$choice])) {
				$this->args[0] = $category;
				$this->args[1] = $cases[$choice];
				$this->_run($this->_parseArgs(), $this->_runnerOptions());
				break;
			}

			if (is_string($choice) && in_array($choice, $cases)) {
				$this->args[0] = $category;
				$this->args[1] = $choice;
				$this->_run($this->_parseArgs(), $this->_runnerOptions());
				break;
			}

			if ($choice === 'q') {
				break;
			}
		}
	}

	/**
	 * Runs the test case from $runnerArgs
	 *
	 * @param array $runnerArgs list of arguments as obtained from _parseArgs()
	 * @param array $options    list of options as constructed by _runnerOptions()
	 *
	 * @return void
	 */
	protected function _run($runnerArgs, $options = array()) {
		restore_error_handler();
		restore_error_handler();

		$testCli = new CakeTestSuiteCommand('CakeTestLoader', $runnerArgs);
		$testCli->run($options);
	}

	/**
	 * Converts the options passed to the shell as options for the PHPUnit cli runner
	 *
	 * @return array Array of params for CakeTestDispatcher
	 */
	protected function _runnerOptions() {
		$options = array();
		$params = $this->params;
		unset($params['help']);

		if (!empty($params['no-colors'])) {
			unset($params['no-colors'], $params['colors']);
		} else {
			$params['colors'] = TRUE;
		}

		foreach ($params as $param => $value) {
			if ($value === FALSE) {
				continue;
			}
			$options[] = '--' . $param;
			if (is_string($value)) {
				$options[] = $value;
			}
		}

		return $options;
	}

}
