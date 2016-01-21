<?php
/**
 * Short description for file.
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://book.cakephp.org/2.0/en/development/testing.html CakePHP(tm) Tests
 * @package       Cake.Test.Fixture
 * @since         CakePHP(tm) v 1.2.0.4667
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Short description for class.
 *
 * @package       Cake.Test.Fixture
 */
class AcoTwoFixture extends CakeTestFixture {

	/**
	 * fields property
	 *
	 * @var array
	 */
	public $fields = array(
			'id' => array('type' => 'integer', 'key' => 'primary'),
			'parent_id' => array('type' => 'integer', 'length' => 10, 'null' => TRUE),
			'model' => array('type' => 'string', 'null' => TRUE),
			'foreign_key' => array('type' => 'integer', 'length' => 10, 'null' => TRUE),
			'alias' => array('type' => 'string', 'default' => ''),
			'lft' => array('type' => 'integer', 'length' => 10, 'null' => TRUE),
			'rght' => array('type' => 'integer', 'length' => 10, 'null' => TRUE)
	);

	/**
	 * records property
	 *
	 * @var array
	 */
	public $records = array(
			array('parent_id' => NULL, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'ROOT', 'lft' => 1, 'rght' => 20),
			array('parent_id' => 1, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'tpsReports', 'lft' => 2, 'rght' => 9),
			array('parent_id' => 2, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'view', 'lft' => 3, 'rght' => 6),
			array('parent_id' => 3, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'current', 'lft' => 4, 'rght' => 5),
			array('parent_id' => 2, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'update', 'lft' => 7, 'rght' => 8),
			array('parent_id' => 1, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'printers', 'lft' => 10, 'rght' => 19),
			array('parent_id' => 6, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'print', 'lft' => 11, 'rght' => 14),
			array('parent_id' => 7, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'lettersize', 'lft' => 12, 'rght' => 13),
			array('parent_id' => 6, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'refill', 'lft' => 15, 'rght' => 16),
			array('parent_id' => 6, 'model' => NULL, 'foreign_key' => NULL, 'alias' => 'smash', 'lft' => 17, 'rght' => 18),
	);
}
