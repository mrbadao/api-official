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
 */
class ApiUsersController extends ApiBaseController
{
	public function login()
	{
		if ($this->Auth->login()) {
			$user = $this->Auth->user();
			var_dump($user);
//			$token = JWT::encode($user, Configure::read('Security.salt'));
//			var_dump($token);
//			$this->set('user', $user);
//			$this->set('token', $token);
//			$this->set('_serialize', array('user', 'token'));
		}
//		var_dump(2);
		die;
	}
}
