<?php

/**
 * @author  : hieunc
 * @project api-official.
 * @Date    : 05/01/2016 10:57
 */
App::uses('AuthComponent', 'Controller/Component');
App::uses('ApiAuthenticateException', 'Lib/Error/ApiExceptions');
App::uses('ErrorConstants', 'Utility/Constant');

/**
 * Class ApiAuthComponent
 */
class ApiAuthComponent extends AuthComponent {
	/**
	 * @param null $user
	 *
	 * @return bool
	 */
	public function login($user = NULL) {
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
	 * Log a user out.
	 *
	 * Returns the logout action to redirect to. Triggers the logout() method of
	 * all the authenticate objects, so they can perform custom logout logic.
	 * AuthComponent will remove the session data, so there is no need to do that
	 * in an authentication object. Logging out will also renew the session id.
	 * This helps mitigate issues with session replays.
	 *
	 * @return string AuthComponent::$logoutRedirect
	 * @see  AuthComponent::$logoutRedirect
	 * @link http://book.cakephp.org/2.0/en/core-libraries/components/authentication.html#logging-users-out
	 */
	public function logout() {
		$this->_setDefaults();
		if (empty($this->_authenticateObjects)) {
			$this->constructAuthenticate();
		}
		$user = $this->user();
		foreach ($this->_authenticateObjects as $auth) {
			$auth->logout($user);
		}
		$this->Session->delete(static::$sessionKey);
		$this->Session->delete('Auth.redirect');
		$this->Session->renew();

		return Router::normalize($this->logoutRedirect);
	}

	/**
	 * @param null $key
	 *
	 * @return array
	 */
	public static function user($key = NULL) {
		return static::$_user;
	}

	protected function _unauthenticated(Controller $controller) {
		if (empty($this->_authenticateObjects)) {
			$this->constructAuthenticate();
		}

		$auth = $this->_authenticateObjects[count($this->_authenticateObjects) - 1];

		if ($auth->unauthenticated($this->request, $this->response)) {
			return FALSE;
		}

		if ($this->_isLoginAction($controller)) {
			if (empty($controller->request->data)) {
				if (!$this->Session->check('Auth.redirect') && env('HTTP_REFERER')) {
					$this->Session->write('Auth.redirect', $controller->referer(NULL, TRUE));
				}
			}

			return TRUE;
		}

		throw new ApiAuthenticateException(ErrorConstants::$API_MESSAGES['lOGIN']['403'], 403);

		$controller->response->statusCode(403);
		$controller->response->send();
		$this->_stop();

		return FALSE;
	}

}