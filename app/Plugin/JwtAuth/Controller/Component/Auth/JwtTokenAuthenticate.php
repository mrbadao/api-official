<?php
	App::uses('BaseAuthenticate', 'Controller/Component/Auth');
	App::uses('ErrorConstants', 'Utility/Constant');

	$path = CakePlugin::path('JwtAuth');
	require_once($path . 'vendor' . DS . 'firebase' . DS . 'php-jwt' . DS . 'Authentication' . DS . 'JWT.php');

	/**
	 * An authentication adapter for AuthComponent.  Provides the ability to authenticate using a Json Web Token
	 *
	 * {{{
	 *    $this->Auth->authenticate = array(
	 *        'Authenticate.Token' => array(
	 *            'fields' => array(
	 *                'username' => 'username',
	 *                'password' => 'password',
	 *                'token' => 'public_key',
	 *            ),
	 *            'parameter' => '_token',
	 *            'header' => 'X-MyApiTokenHeader',
	 *            'userModel' => 'User',
	 *            'scope' => array('User.active' => 1)
	 *        )
	 *    )
	 * }}}
	 *
	 * @author    Ceeram, Florian Krämer, Ronald Chaplin
	 * @copyright Ceeram, Florian Krämer, Ronald Chaplin
	 * @license   MIT
	 * @link      https://github.com/ceeram/Authenticate
	 */
	class JwtTokenAuthenticate extends BaseAuthenticate {
		public $currentDateTime;
		public $components = array('RequestHandler');
		/**
		 * Settings for this object.
		 *
		 * - `fields` The fields to use to identify a user by.
		 * - `parameter` The url parameter name of the token.
		 * - `header` The token header value.
		 * - `userModel` The model name of the User, defaults to User.
		 * - `scope` Additional conditions to use when looking up and authenticating users,
		 *    i.e. `array('User.is_active' => 1).`
		 * - `recursive` The value of the recursive key passed to find(). Defaults to 0.
		 * - `contain` Extra models to contain and store in session.
		 * - `pepper` The public pepper that clients would use to encrypt their token payload
		 *
		 * @var array
		 */
		public $settings = array(
				'fields' => array(
						'username' => 'username',
						'password' => 'password',
						'token' => 'token',
				),
				'parameter' => '_token',
				'header' => 'X_TOKEN',
				'userModel' => 'User',
				'tokenModel' => 'AccessTokens',
				'authTokenModel' => 'authToken',
				'scope' => array(),
				'recursive' => 0,
				'contain' => array('authToken'),
				'pepper' => '123'
		);

		/**
		 * Constructor
		 *
		 * @param ComponentCollection $collection The Component collection used on this request.
		 * @param array               $settings   Array of settings to use.
		 *
		 * @throws CakeException
		 */
		public function __construct(ComponentCollection $collection, $settings) {
			$this->currentDateTime = new DateTime();
			parent::__construct($collection, $settings);
			if (empty($this->settings['parameter']) && empty($this->settings['header'])) {
				throw new CakeException(__d('jwt_auth', 'You need to specify token parameter and/or header'));
			}
		}

		/**
		 * Unused since this a stateless authentication
		 *
		 * @param CakeRequest  $request  The request object
		 * @param CakeResponse $response response object.
		 *
		 * @return mixed.  Always false
		 */
		public function authenticate(CakeRequest $request, CakeResponse $response) {
			//handle json post
			$userLoginInfo = json_decode(utf8_encode(trim(file_get_contents('php://input'))), TRUE);

			if (empty($userLoginInfo) ||
					!isset($userLoginInfo['data']) ||
					!isset($userLoginInfo['data']['username']) ||
					!isset($userLoginInfo['data']['password'])
			) {
				throw new ApiAuthenticateException(ErrorConstants::$API_MESSAGES['lOGIN']['403'], 403);
			}

			$userModel = $this->settings['userModel'];
			$tokenModel = $this->settings['tokenModel'];
			list($plugin, $model) = pluginSplit($userModel);

			$fields = $this->settings['fields'];
			$conditions = array(
					$model . '.' . $fields['username'] => $userLoginInfo['data']['username'],
					$model . '.' . $fields['password'] => Security::hash($userLoginInfo['data']['password'], 'sha256', TRUE),
			);

			if (!empty($this->settings['scope'])) {
				$conditions = array_merge($conditions, $this->settings['scope']);
			}

			$userModelObj = ClassRegistry::init($userModel);
			$userQueryResult = $userModelObj->find('first', array(
					'conditions' => $conditions,
					'recursive' => (int)$this->settings['recursive'],
					'contain' => $this->settings['contain'],
			));

			if (empty($userQueryResult) || empty($userQueryResult[$model])) {
				return FALSE;
			}

			$tokenModelObj = ClassRegistry::init($tokenModel);
			$tokenEndcodeData = $userQueryResult[$model];
			unset($tokenEndcodeData[$fields['password']]);
			unset($tokenEndcodeData['display_name']);
			unset($tokenEndcodeData['created']);
			unset($tokenEndcodeData['modified']);

			$tokenData = array(
					"user_id" => $userQueryResult[$model][$userModelObj->primaryKey],
					"token" => JWT::encode($tokenEndcodeData, Configure::read('Security.salt')),
					"client_ip" => $request->clientIp(),
					"expired" => ($this->currentDateTime->add(new DateInterval('P1D'))->getTimestamp()),
			);
			if (!$tokenModelObj->save($tokenData)) {
				return FALSE;
			}

			$user = $userQueryResult[$model];
			$user['api_access_key'] = $tokenData['token'];
			unset($userQueryResult[$model]);

			return array_merge($user, $userQueryResult);
		}

		/**
		 * Handle unauthenticated access attempt.
		 *
		 * @param CakeRequest  $request  A request object.
		 * @param CakeResponse $response A response object.
		 *
		 * @return mixed Either true to indicate the unauthenticated request has been
		 *  dealt with and no more action is required by AuthComponent or void (default).
		 */
		public function unauthenticated(CakeRequest $request, CakeResponse $response) {
			//get user if loggined
			$user = $this->getUser($request);
			if ($user) {
				return $user;
			}

			return FALSE;
		}

		/**
		 * Get token information from the request.
		 *
		 * @param CakeRequest $request Request object.
		 *
		 * @return mixed Either false or an array of user information
		 */
		public function getUser(CakeRequest $request) {
			$token = $this->_getToken($request);
			if ($token) {
				return $this->_findUser($token);
			}

			return FALSE;
		}

		/**
		 * @param CakeRequest $request
		 *
		 * @return mixed
		 */
		private function _getToken(CakeRequest $request) {
			if (!empty($this->settings['header'])) {
				$token = $request->header($this->settings['header']);
				if ($token) {
					return $token;
				}
			}

			if (!empty($this->settings['parameter']) && !empty($request->query[$this->settings['parameter']])) {
				return $request->query[$this->settings['parameter']];
			}

			return FALSE;

		}

		/**
		 * Find a user record.
		 *
		 * @param string $token
		 * @param string $password
		 *
		 * @return Mixed Either false on failure, or an array of user data.
		 */
		public function _findUser($token, $password = NULL) {
			try {
				$objDecode = JWT::decode($token, Configure::read('Security.salt'), array('HS256'));
			} catch (Exception $e) {
				throw new ApiAuthenticateException(ErrorConstants::$API_MESSAGES['lOGIN']['4031'], 4031);
			}

			if (isset($objDecode->record)) {
				// Trick to convert object of stdClass to array. Typecasting to
				// array doesn't convert property values which are themselves objects.
				return json_decode(json_encode($objDecode->record), TRUE);
			}
			$tokenModel = $this->settings['tokenModel'];
			list($plugin, $model) = pluginSplit($tokenModel);
			$accessTokenModel = ClassRegistry::init($tokenModel);

			$fields = $this->settings['fields'];

			$conditions = array(
					$model . '.user_id' => $objDecode->user_id,
					$model . '.' . $fields['token'] => $token,
					$model . '.expired >' => $this->currentDateTime->getTimestamp(),
			);

			if (!empty($this->settings['scope'])) {
				$accessTokenModel->belongsTo[$this->settings['userModel']]['conditions'] = array_merge(array(
						$this->settings['userModel'] . '.user_id' => $objDecode->user_id,
				), $this->settings['scope']);
			} else {
				$accessTokenModel->belongsTo[$this->settings['userModel']]['conditions'] = array(
						$this->settings['userModel'] . '.user_id' => $objDecode->user_id,
				);
			}

			$result = $accessTokenModel->find('first', array(
					'conditions' => $conditions,
					'recursive' => (int)$this->settings['recursive'],
					'contain' => $this->settings['contain'],
			));

			if (empty($result) || empty($result[$model]) || empty($this->settings['userModel'])) {
				return FALSE;
			}

			$user = $result[$this->settings['userModel']];
			unset($result[$this->settings['userModel']]);

			return array_merge($user, $result);
		}
	}
