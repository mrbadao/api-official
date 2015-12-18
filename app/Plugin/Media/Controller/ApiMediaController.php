<?php

/**
 * @author: hieunc
 * @project api-official.
 * @Date: 18/12/2015
 * @Time: 14:53
 */
App::uses('ApiBaseController', 'Media.Controller');;

class ApiMediaController extends ApiBaseController
{
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->modelClass = "Media";
	}

	public function getMedia()
	{
		return json_encode($this->Media->find('all'));
	}
}