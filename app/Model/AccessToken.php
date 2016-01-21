<?php

/**
 * @author  : hieunc
 * @project api-official.
 * @Date    : 22/12/2015 14:44
 */
App::uses('AppModel', 'Model');

class AccessToken extends AppModel {
	public $primaryKey = 'token_id';
	public $belongsTo = array('User');
}