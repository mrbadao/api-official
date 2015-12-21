<?php

/**
 * @author: hieunc
 * @project api-official.
 * @Date: 21/12/2015
 * @Time: 15:30
 */
class AppError
{
	public static function handleError($code, $description, $file = null, $line = null, $context = null)
	{
		list(, $level) = ErrorHandler::mapErrorCode($code);
		if ($level === LOG_ERR) {
			// Ignore fatal error. It will keep the PHP error message only
			return false;
		}
		return ErrorHandler::handleError(
			$code,
			$description,
			$file,
			$line,
			$context
		);
	}
}