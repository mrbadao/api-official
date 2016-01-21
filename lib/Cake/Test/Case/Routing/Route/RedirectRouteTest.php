<?php
/**
 * CakeRequest Test case file.
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
 * @package       Cake.Test.Case.Routing.Route
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('RedirectRoute', 'Routing/Route');
App::uses('CakeResponse', 'Network');
App::uses('Router', 'Routing');

/**
 * test case for RedirectRoute
 *
 * @package       Cake.Test.Case.Routing.Route
 */
class RedirectRouteTest extends CakeTestCase {

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		Configure::write('Routing', array('admin' => NULL, 'prefixes' => array()));
		Router::reload();
	}

	/**
	 * test the parsing of routes.
	 *
	 * @return void
	 */
	public function testParsing() {
		$route = new RedirectRoute('/home', array('controller' => 'posts'));
		$route->stop = FALSE;
		$route->response = $this->getMock('CakeResponse', array('_sendHeader'));
		$route->parse('/home');
		$header = $route->response->header();
		$this->assertEquals(Router::url('/posts', TRUE), $header['Location']);

		$route = new RedirectRoute('/home', array('controller' => 'posts', 'action' => 'index'));
		$route->stop = FALSE;
		$route->response = $this->getMock('CakeResponse', array('_sendHeader'));
		$route->parse('/home');
		$header = $route->response->header();
		$this->assertEquals(Router::url('/posts', TRUE), $header['Location']);
		$this->assertEquals(301, $route->response->statusCode());

		$route = new RedirectRoute('/google', 'http://google.com');
		$route->stop = FALSE;
		$route->response = $this->getMock('CakeResponse', array('_sendHeader'));
		$route->parse('/google');
		$header = $route->response->header();
		$this->assertEquals('http://google.com', $header['Location']);

		$route = new RedirectRoute('/posts/*', array('controller' => 'posts', 'action' => 'view'), array('status' => 302));
		$route->stop = FALSE;
		$route->response = $this->getMock('CakeResponse', array('_sendHeader'));
		$route->parse('/posts/2');
		$header = $route->response->header();
		$this->assertEquals(Router::url('/posts/view', TRUE), $header['Location']);
		$this->assertEquals(302, $route->response->statusCode());

		$route = new RedirectRoute('/posts/*', array('controller' => 'posts', 'action' => 'view'), array('persist' => TRUE));
		$route->stop = FALSE;
		$route->response = $this->getMock('CakeResponse', array('_sendHeader'));
		$route->parse('/posts/2');
		$header = $route->response->header();
		$this->assertEquals(Router::url('/posts/view/2', TRUE), $header['Location']);

		$route = new RedirectRoute('/posts/*', '/test', array('persist' => TRUE));
		$route->stop = FALSE;
		$route->response = $this->getMock('CakeResponse', array('_sendHeader'));
		$route->parse('/posts/2');
		$header = $route->response->header();
		$this->assertEquals(Router::url('/test', TRUE), $header['Location']);

		$route = new RedirectRoute('/my_controllers/:action/*', array('controller' => 'tags', 'action' => 'add'), array('persist' => TRUE));
		$route->stop = FALSE;
		$route->response = $this->getMock('CakeResponse', array('_sendHeader'));
		$route->parse('/my_controllers/do_something/passme/named:param');
		$header = $route->response->header();
		$this->assertEquals(Router::url('/tags/add/passme/named:param', TRUE), $header['Location']);

		$route = new RedirectRoute('/my_controllers/:action/*', array('controller' => 'tags', 'action' => 'add'));
		$route->stop = FALSE;
		$route->response = $this->getMock('CakeResponse', array('_sendHeader'));
		$route->parse('/my_controllers/do_something/passme/named:param');
		$header = $route->response->header();
		$this->assertEquals(Router::url('/tags/add', TRUE), $header['Location']);

		$route = new RedirectRoute('/:lang/my_controllers', array('controller' => 'tags', 'action' => 'add'), array('lang' => '(nl|en)', 'persist' => array('lang')));
		$route->stop = FALSE;
		$route->response = $this->getMock('CakeResponse', array('_sendHeader'));
		$route->parse('/nl/my_controllers/');
		$header = $route->response->header();
		$this->assertEquals(Router::url('/tags/add/lang:nl', TRUE), $header['Location']);

		Router::$routes = array(); // reset default routes
		Router::connect('/:lang/preferred_controllers', array('controller' => 'tags', 'action' => 'add'), array('lang' => '(nl|en)', 'persist' => array('lang')));
		$route = new RedirectRoute('/:lang/my_controllers', array('controller' => 'tags', 'action' => 'add'), array('lang' => '(nl|en)', 'persist' => array('lang')));
		$route->stop = FALSE;
		$route->response = $this->getMock('CakeResponse', array('_sendHeader'));
		$route->parse('/nl/my_controllers/');
		$header = $route->response->header();
		$this->assertEquals(Router::url('/nl/preferred_controllers', TRUE), $header['Location']);
	}

}
