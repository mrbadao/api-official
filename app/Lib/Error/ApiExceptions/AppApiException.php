<?php

/**
 * @author: hieunc
 * @project api-official.
 * @Date: 05/01/2016 12:36
 */
class AppApiException extends CakeException
{
	public function __construct($message, $code)
	{
		parent::__construct($message, $code);
	}

}