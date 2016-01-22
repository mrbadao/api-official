<?php

/**
 * @author  : hieunc
 * @project api-official.
 * @Date    : 08/01/2016 09:44
 */
class ErrorConstants {
	public static $API_MESSAGES = array(
			"lOGIN" => array(
					'403' => "Login Require.",
					'4030' => "Login failed.",
					'4031' => "Api access key invalid."
			),
			"CATEGORY"=>array(
				"CREATE" => array(
					'404' => "Empty data receive."
				)
			),
	);
}