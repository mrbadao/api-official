<?php

/**
 * Created by PhpStorm.
 * User: hieunc
 * Date: 16/12/2015
 * Time: 13:52
 */

App::uses('AppController', 'Controller');

class MediasController extends AppController
{
	public $helpers = array('Html', 'Form');

	public function index()
	{
		$this->set('medias', $this->Media->find('all'));
	}

	public function view($id = null)
	{
		if (!$id) {
			throw new NotFoundException(__('Invalid Media'));
		}

		$media = $this->Media->findByMedia_id($id);
		if (!$media) {
			throw new NotFoundException(__('Invalid Media'));
		}
		$this->set('media', $media);
	}

	public function add()
	{
		if ($this->request->is("post")) {
			$this->Media->create();
			if ($this->Media->save($this->request->data)) {
				$this->Flash->success(__('Your media post has been saved.'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error(__('Unable to add your post.'));
		}
	}
}