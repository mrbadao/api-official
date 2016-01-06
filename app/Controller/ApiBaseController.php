<?php
/**
 * @author: hieunc
 * @project api-official.
 * @Date: 18/12/2015
 * @Time: 14:53
 */

App::uses('Controller', 'Controller');
App::uses('ApiAuthComponent', 'Controller/Component');

/**
 * Class ApiBaseController
 * @property ApiAuthComponent ApiAuth
 */
class ApiBaseController extends Controller
{
	/**
	 * @contant default response type
	 */
	const default_response_content_type = "application/json; charset=utf-8";

	const default_access_control_allow_origin = "Access-Control-Allow-Origin";

	public $components = array(
		'ApiAuth' => array(
			'loginAction' => array(
				'controller' => 'apiUsers',
				'action' => 'login',
				'plugin' => null
			),
			'loginRedirect' => array(
				'controller' => 'apiUsers',
				'action' => 'login'
			),
			'logoutRedirect' => array(
				'controller' => 'apiUsers',
				'action' => 'login'
			),
			'authenticate' => array(
				'JwtAuth.JwtToken' => array(
					'fields' => array(
						'username' => 'username',
						'password' => 'password',
						'token' => 'api_access_key',
					),
					'parameter' => '_token',
					'header' => 'X-TOKEN',
					'userModel' => 'User',
					'scope' => array('User.active' => 1)
				)
			)
		)
	);

	/**
	 * ApiBaseController constructor.
	 * @param CakeRequest|null $request
	 * @param CakeResponse|null $response
	 */
	public function __construct($request, $response)
	{
		parent::__construct($request, $response);
		$this->layout = null;
		$this->autoRender = false;
		$this->response->type(self::default_response_content_type);
	}

	/**
	 *
	 * Called after the controller action is run, but before the view is rendered. You can use this method
	 * to perform logic or set view variables that are required on every request.
	 *
	 * @return void
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->response->type(self::default_response_content_type);
		$this->response->header('Access-Control-Allow-Origin', '*');
	}

	/**
	 * @param int $httpStatus
	 * @param array $dataBody
	 * @param array $errors
	 * @return string
	 */
	protected static function getJsonResponseData($httpStatus = 200, $dataBody = array(), $errors = array())
	{
		return json_encode(array(
			"status" => $httpStatus,
			"data" => $dataBody,
			"errors" => $errors
		));
	}
}
