<?php

/**
 * @author: hieunc
 * @project api-official.
 * @Date: 05/01/2016 12:36
 */
//App::uses('HttpException', 'Lib/Error');

class ApiAuthenticateException extends CakeException
{
	protected $_messageTemplate = 'Seems that %s is missing.';

	public function __construct($message, $code)
	{
		parent::__construct($message, $code);
		$this->responseHeader("Content-Type", ApiBaseController::default_response_content_type);
//		var_dump($this->responseHeader());
	}

}