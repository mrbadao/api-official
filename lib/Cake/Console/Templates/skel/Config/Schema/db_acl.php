<?php
/**
 * This is Acl Schema file
 *
 * Use it to configure database for ACL
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config.Schema
 * @since         CakePHP(tm) v 0.2.9
 */

/**
 * Using the Schema command line utility
 * cake schema run create DbAcl
 */
class DbAclSchema extends CakeSchema {

	/**
	 * ACO - Access Control Object - Something that is wanted
	 */
	public $acos = array(
			'id' => array('type' => 'integer', 'null' => FALSE, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'parent_id' => array('type' => 'integer', 'null' => TRUE, 'default' => NULL, 'length' => 10),
			'model' => array('type' => 'string', 'null' => TRUE),
			'foreign_key' => array('type' => 'integer', 'null' => TRUE, 'default' => NULL, 'length' => 10),
			'alias' => array('type' => 'string', 'null' => TRUE),
			'lft' => array('type' => 'integer', 'null' => TRUE, 'default' => NULL, 'length' => 10),
			'rght' => array('type' => 'integer', 'null' => TRUE, 'default' => NULL, 'length' => 10),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	/**
	 * ARO - Access Request Object - Something that wants something
	 */
	public $aros = array(
			'id' => array('type' => 'integer', 'null' => FALSE, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'parent_id' => array('type' => 'integer', 'null' => TRUE, 'default' => NULL, 'length' => 10),
			'model' => array('type' => 'string', 'null' => TRUE),
			'foreign_key' => array('type' => 'integer', 'null' => TRUE, 'default' => NULL, 'length' => 10),
			'alias' => array('type' => 'string', 'null' => TRUE),
			'lft' => array('type' => 'integer', 'null' => TRUE, 'default' => NULL, 'length' => 10),
			'rght' => array('type' => 'integer', 'null' => TRUE, 'default' => NULL, 'length' => 10),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	/**
	 * Used by the Cake::Model:Permission class.
	 * Checks if the given $aro has access to action $action in $aco.
	 */
	public $aros_acos = array(
			'id' => array('type' => 'integer', 'null' => FALSE, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'aro_id' => array('type' => 'integer', 'null' => FALSE, 'length' => 10, 'key' => 'index'),
			'aco_id' => array('type' => 'integer', 'null' => FALSE, 'length' => 10),
			'_create' => array('type' => 'string', 'null' => FALSE, 'default' => '0', 'length' => 2),
			'_read' => array('type' => 'string', 'null' => FALSE, 'default' => '0', 'length' => 2),
			'_update' => array('type' => 'string', 'null' => FALSE, 'default' => '0', 'length' => 2),
			'_delete' => array('type' => 'string', 'null' => FALSE, 'default' => '0', 'length' => 2),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ARO_ACO_KEY' => array('column' => array('aro_id', 'aco_id'), 'unique' => 1))
	);

	/**
	 * Before event.
	 *
	 * @param array $event The event data.
	 *
	 * @return bool success
	 */
	public function before($event = array()) {
		return TRUE;
	}

	/**
	 * After event.
	 *
	 * @param array $event The event data.
	 *
	 * @return void
	 */
	public function after($event = array()) {
	}

}
