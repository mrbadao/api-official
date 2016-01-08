<?php

/**
 * @author: hieunc
 * @project api-official.
 * @Date: 18/12/2015
 * @Time: 14:53
 */
App::uses('ApiBaseController', 'Controller');

/**
 * Class ApiMediaController
 * @property Media Media
 */
class ApiMediaController extends ApiBaseController
{
	/**
	 *
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->modelClass = "Media";
	}

	/**
	 * @return string
	 */
	public function getMedia()
	{
		return self::getJsonResponseData(200, $this->Media->find('all'));
	}

	public function postMedia()
	{
		if (!$this->request->data || empty($this->request->data('data'))) {
			return self::getJsonResponseData(500, array(), array("data error."));
		}

		if ($this->Media->save(array("Media" => $this->request->data("data")))) {
			return self::getJsonResponseData(200, array("success" => true));
		} else {
			return self::getJsonResponseData(500, array(), $this->Media->validationErrors);
		}
	}

}