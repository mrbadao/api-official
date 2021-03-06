<?php

App::uses('AppModel', 'Model');

class Media extends AppModel {
	/**
	 * @var array
	 */
	public $validate = array(
			'media_name' => array(
					'between' => array(
							'rule' => array('lengthBetween', 1, 128),
							'required' => TRUE,
							'message' => 'Between 3 to 100 characters'
					),
					'unique' => array(
							'rule' => array('isUnique', array('media_name', 'media_mime_type'), FALSE),
							'message' => 'This media has already been created.'
					),
			),
			'created' => array(
					'rule' => 'date',
					'message' => 'Enter a valid date',
					'allowEmpty' => TRUE
			),
			'modified' => array(
					'rule' => 'date',
					'message' => 'Enter a valid date',
					'allowEmpty' => TRUE
			),
	);
}
