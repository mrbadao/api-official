<?php
	/**
	 * @author  : hieunc
	 * @project api-official.
	 * @Date    : 18/12/2015
	 * @Time    : 14:53
	 */

	App::uses('ApiBaseController', 'Controller');
	App::uses('Category', 'Category.Model');
	App::uses('ErrorConstants', 'Utility/Constant');

	/**
	 * Class ApiCategoryController
	 *
	 * @property Category Category
	 */
	class ApiCategoryController extends ApiBaseController {
		public $uses = array('Category.Category');

		public function getcategory() {
			var_dump($this->Category->find('first'));
			die;

		}

		public function createCategory() {
			if(empty($this->request->data)){
				return self::getJsonResponseData(404, array(),ErrorConstants::$API_MESSAGES['CATEGORY']['CREATE']['404']);
			}
			//return self::getJsonResponseData(200, $this->Media->find('all'));
			if ($result = $this->Category->saveAll($this->request->data, array('deep' => TRUE))) {
				return self::getJsonResponseData(200, $this->request->data);
			} else {
				var_dump($this->Category->validationErrors());
			}

			die;
		}
	}
