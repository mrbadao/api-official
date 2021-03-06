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
class DeviceTypeFixture extends CakeTestFixture {

	/**
	 * fields property
	 *
	 * @var array
	 */
	public $fields = array(
			'id' => array('type' => 'integer', 'key' => 'primary'),
			'device_type_category_id' => array('type' => 'integer', 'null' => FALSE),
			'feature_set_id' => array('type' => 'integer', 'null' => FALSE),
			'exterior_type_category_id' => array('type' => 'integer', 'null' => FALSE),
			'image_id' => array('type' => 'integer', 'null' => FALSE),
			'extra1_id' => array('type' => 'integer', 'null' => FALSE),
			'extra2_id' => array('type' => 'integer', 'null' => FALSE),
			'name' => array('type' => 'string', 'null' => FALSE),
			'order' => array('type' => 'integer', 'null' => FALSE)
	);

	/**
	 * records property
	 *
	 * @var array
	 */
	public $records = array(
			array('device_type_category_id' => 1, 'feature_set_id' => 1, 'exterior_type_category_id' => 1, 'image_id' => 1, 'extra1_id' => 1, 'extra2_id' => 1, 'name' => 'DeviceType 1', 'order' => 0)
	);
}
