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

		public function getCategories() {
			return self::getJsonResponseData(200, $this->Category->find('all', array(
					'recursive' => 1,
			)));
		}

		public function createCategory() {
			if (empty($this->request->data)) {
				return self::getJsonResponseData(404, array(), ErrorConstants::$API_MESSAGES['CATEGORY']['CREATE']['404']);
			}

			if ($result = $this->Category->saveAll($this->request->data['data'], array('deep' => TRUE))) {
				return self::getJsonResponseData(200, $this->Category->find('first', array(
						'recursive' => 2,
						'conditions' => array('Category.id' => $this->Category->getLastInsertID()),
				)));
			} else {
				var_dump($this->Category->validationErrors);
			}

			die;
		}
	}
