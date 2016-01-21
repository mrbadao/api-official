<?php
/**
 * @author  : hieunc
 * @project api-official.
 * @Date    : 05/01/2016 12:36
 */

/**
 * Class AppApiException
 */
class AppApiException extends CakeException {
	/**
	 * AppApiException constructor.
	 *
	 * @param array|string $message
	 * @param int          $code
	 */
	public function __construct($message, $code) {
		parent::__construct($message, $code);
	}

}