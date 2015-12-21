<?php
/**
 * @author: hieunc
 * @project api-official.
 * @Date: 18/12/2015
 * @Time: 14:53
 */

App::uses('Controller', 'Controller');

/**
 * Class ApiBaseController
 */
class ApiBaseController extends Controller
{
	/**
	 * @contant default response type
	 */
	const default_response_content_type = "application/json; charset=utf-8";
	public $components = array('RequestHandler');

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
		parent::beforeRender();
		$this->response->type(self::default_response_content_type);
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
