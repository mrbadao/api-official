<?php

/**
 * @author: hieunc
 * @project api-official.
 * @Date: 05/01/2016 10:57
 */
App::uses('AuthComponent', 'Controller/Component');
App::uses('ApiAuthenticateException', 'Lib/Error/ApiExceptions');

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

	/**
	 * Handles unauthenticated access attempt. First the `unathenticated()` method
	 * of the last authenticator in the chain will be called. The authenticator can
	 * handle sending response or redirection as appropriate and return `true` to
	 * indicate no furthur action is necessary. If authenticator returns null this
	 * method redirects user to login action. If it's an ajax request and
	 * $ajaxLogin is specified that element is rendered else a 403 http status code
	 * is returned.
	 *
	 * @param Controller $controller A reference to the controller object.
	 * @return bool True if current action is login action else false.
	 */
	protected function _unauthenticated(Controller $controller)
	{
		if (empty($this->_authenticateObjects)) {
			$this->constructAuthenticate();
		}

		$auth = $this->_authenticateObjects[count($this->_authenticateObjects) - 1];
		if ($auth->unauthenticated($this->request, $this->response)) {
			return false;
		}

		if ($this->_isLoginAction($controller)) {
			if (empty($controller->request->data)) {
				if (!$this->Session->check('Auth.redirect') && env('HTTP_REFERER')) {
					$this->Session->write('Auth.redirect', $controller->referer(null, true));
				}
			}
			return true;
		}

		throw new ApiAuthenticateException('Login required.', 403);

		$controller->response->statusCode(403);
		$controller->response->send();
		$this->_stop();
		return false;
	}

}