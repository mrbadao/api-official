<?php
	/**
	 * Routes configuration
	 *
	 * In this file, you set up routes to your controllers and their actions.
	 * Routes are very important mechanism that allows you to freely connect
	 * different URLs to chosen controllers and their actions (functions).
	 *
	 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
	 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
	 *
	 * Licensed under The MIT License
	 * For full copyright and license information, please see the LICENSE.txt
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
	 * @link          http://cakephp.org CakePHP(tm) Project
	 * @package       app.Config
	 * @since         CakePHP(tm) v 0.2.9
	 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
	 */

// Define route for Plugin Category
//api call
	Router::connect(
			'/api/category/getcategories',
			array(
					'plugin' => 'category',
					'controller' => 'apiCategory',
					'action' => 'getCategories'
			)
	);
	Router::connect(
			'/api/category/createcategory',
			array(
					'plugin' => 'category',
					'controller' => 'apiCategory',
					'action' => 'createCategory',
					'[method]' => 'POST',
			)
	);
	Router::connect(
			'/api/category/getcategory/:id',
			array(
					'plugin' => 'category',
					'controller' => 'apiCategory',
					'action' => 'getCategory'
			),
			array(
					'pass' => array('id'),
					':id' => '[\d]+'
			)
	);
