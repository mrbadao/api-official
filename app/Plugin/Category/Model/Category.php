<?php

	App::uses('AppModel', 'Model');
	App::uses('CategoryName', 'Category.Model');

	class Category extends AppModel {
		public $useTable = 'category';

		public $hasMany = array(
				'CategoryName' => array(
						'className' => 'Category.CategoryName',
						//'foreignKey' => 'category_id'
				),
				'CategorySeoLink' => array(
						'className' => 'Category.CategorySeoLink',
						//'foreignKey' => 'category_id'
				)
		);
	}
