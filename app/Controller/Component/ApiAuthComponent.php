<?php

/**
 * @author: hieunc
 * @project api-official.
 * @Date: 05/01/2016 10:57
 */
App::uses('AuthComponent', 'Controller/Component');

/**
 * Class ApiAuthComponent
 */
class ApiAuthComponent extends AuthComponent
{
	/**
	 * @param null $key
	 * @return array
	 */
	public static function user($key = null)
	{
		return static::$_user;
	}

	/**
	 * @param null $user
	 * @return bool
	 */
	public function login($user = null)
	{
		$this->_setDefaults();
		if (empty($user)) {
			$user = $this->identify($this->request, $this->response);
		}
		if ($user) {
			$event = new CakeEvent('Auth.afterIdentify', $this, array('user' => $user));
			$this->_Collection->getController()->getEventManager()->dispatch($event);
		}
		static::$_user = $user;
		return (bool)$user;
	}

}