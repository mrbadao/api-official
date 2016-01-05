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
		throw new ApiAuthenticateException("Login Error", 403);
	}


}
