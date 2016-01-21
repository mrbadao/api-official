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
			$dumpData = array('Category' => array());
			["CategoryName"]=> array(2) {
				[0]=> array (
					["id"]=> string(1) "1" ["category_id"]=> string(1) "1" ["lang_id"]=> string(1) "1" ["name"]=> string(8) "Articles" ) [1]=> array(4) (
					["id"]=> string(1) "2" ["category_id"]=> string(1) "1" ["lang_id"]=> string(1) "2" ["name"]=> string(11) "Bài Viết" ) )
			$this->Category->new
			var_dump($this->request->data);
			die;
		}
	}
