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

	public function beforeFilter()
	{
		$this->response->type(self::default_response_content_type);
	}
}
