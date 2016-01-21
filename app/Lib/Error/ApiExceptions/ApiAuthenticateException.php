<?php
	/**
	 * @author  : hieunc
	 * @project api-official.
	 * @Date    : 05/01/2016 12:36
	 */

	/**
	 * Class ApiAuthenticateException
	 */
	class ApiAuthenticateException extends CakeException {
		/**
		 * ApiAuthenticateException constructor.
		 *
		 * @param array|string $message
		 * @param int          $code
		 */
		public function __construct($message, $code) {
			parent::__construct($message, $code);
		}

	}