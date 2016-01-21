<?php

	/**
	 * @author  : hieunc
	 * @project api-official.
	 * @Date    : 21/12/2015
	 * @Time    : 16:39
	 */
	App::uses('ExceptionRenderer', 'Error');

	class AppExceptionRenderer extends ExceptionRenderer {
		public function apiAuthenticate($error) {
			$this->appApi($error);

		}

		public function appApi($error) {
			$this->controller->response->type(ApiBaseController::default_response_content_type);
			echo json_encode(array(
					"status" => $error->getCode(),
					"data" => array(),
					"errors" => array($error->getMessage()),
			), TRUE);
			$this->controller->response->send();
		}

		public function apiTokenInvalid($error) {
			$this->appApi($error);
		}

		public function notFound($error) {
			print_r($error->getCode());
			print_r($error->getMessage());
		}

//	public function badRequest($error)
//	{
//		$this->controller->beforeFilter();
//		$this->controller->set('title_for_layout', 'Bad Request');
//		$this->controller->render(' / Errors / error400');
//		$this->controller->response->send();
//	}
//
//	public function forbidden($error)
//	{
//		$this->controller->beforeFilter();
//		$this->controller->set('title_for_layout', 'Forbidden Access');
//		$this->controller->render('/Errors/error403');
//		$this->controller->response->send();
//	}
//
//	public function methodNotAllowed($error)
//	{
//		$this->controller->beforeFilter();
//		$this->controller->set('title_for_layout', 'Not Allowed');
//		$this->controller->render(' / Errors / error405');
//		$this->controller->response->send();
//	}
//
//	public function internalError($error)
//	{
//		$this->controller->beforeFilter();
//		$this->controller->set('title_for_layout', 'Internal Server Error');
//		$this->controller->render(' / Errors / error500');
//		$this->controller->response->send();
//	}
//
//	public function notImplemented($error)
//	{
//		$this->controller->beforeFilter();
//		$this->controller->set('title_for_layout', 'Method not implemented');
//		$this->controller->render(' / Errors / error501');
//		$this->controller->response->send();
//	}
//
//	public function missingController($error)
//	{
//		$this->notFound($error);
//	}
//
//	public function missingAction($error)
//	{
//		$this->notFound($error);
//	}
//
//	public function missingView($error)
//	{
//		$this->notFound($error);
//	}
//
//	public function fatalError($error)
//	{
//		$this->notFound($error);
//	}
//
//	public function missingLayout($error)
//	{
//		$this->internalError($error);
//	}
//
//	public function missingHelper($error)
//	{
//		$this->internalError($error);
//	}
//
//	public function missingBehavior($error)
//	{
//		$this->internalError($error);
//	}
//
//	public function missingComponent($error)
//	{
//		$this->internalError($error);
//	}
//
//	public function missingTask($error)
//	{
//		$this->internalError($error);
//	}
//
//	public function missingShell($error)
//	{
//		$this->internalError($error);
//	}
//
//	public function missingShellMethod($error)
//	{
//		$this->internalError($error);
//	}
//
//	public function missingDatabase($error)
//	{
//		$this->internalError($error);
//	}
//
//	public function missingConnection($error)
//	{
//		$this->internalError($error);
//	}
//
//	public function missingTable($error)
//	{
//		$this->internalError($error);
//	}
//
//	public function privateAction($error)
//	{
//		$this->internalError($error);
//	}
	}

