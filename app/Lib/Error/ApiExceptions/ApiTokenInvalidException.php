<?php

/**
 * @author: hieunc
 * @project api-official.
 * @Date: 05/01/2016 12:36
 */
App::uses('ErrorConstants', 'Utility/Constant');

class ApiTokenInvalidException extends CakeException
{
	public function __construct($message, $code)
	{
		parent::__construct($message, $code);
	}

}