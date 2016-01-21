<?php
	/**
	 * @author  : hieunc
	 * @project api-official.
	 * @Date    : 18/12/2015
	 * @Time    : 14:53
	 */

	App::uses('ApiBaseController', 'Controller');
	App::uses('Category', 'Category.Model');

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
			$dumpData = array(
					'Category' => array('create' => date('Y-m-d H:m:s')),
					"CategoryName" => array(
							array(

									"lang_id" => "1",
									"name" => "Articles"
							),
							array(
									"lang_id" => "2",
									"name" => "Bài Viết"
							)
					)
			);

			$this->Category->newEntity($dumpData, [
					'associated' => ['CategoryName']
			]);
			var_dump($this->request->data);
			die;
		}
	}
