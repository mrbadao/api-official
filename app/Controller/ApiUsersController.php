<?php
/**
 * @author: hieunc
 * @project api-official.
 * @Date: 18/12/2015
 * @Time: 14:53
 */

App::uses('ApiBaseController', 'Controller');

/**
 * Class ApiUsersController
 *
 */
class ApiUsersController extends ApiBaseController
{
	public function login()
	{
		if ($this->ApiAuth->login()) {
			$user = $this->ApiAuth->user();
			unset($user['password']);
			return self::getJsonResponseData(200, $user);
		}
		return self::getJsonResponseData(500, array(), array("Login error."));
	}


}
