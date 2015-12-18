<?php
/**
 * @author: hieunc
 * @project api-official.
 * @Date: 18/12/2015
 * @Time: 14:53
 */

App::uses('BaseController', 'Media.Controller');

/**
 * Class MediaController
 *
 */
class MediaController extends BaseController
{
	/**
	 * @medthod index()
	 */
	public function index()
	{
		var_dump($this->layoutPath);
	}
}