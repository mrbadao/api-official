<?php

App::uses('DataSource', 'Model/Datasource');

class TestSource extends DataSource {

	public function describe($model) {
		return compact('model');
	}

	public function listSources($data = NULL) {
		return array('test_source');
	}

	public function create(Model $model, $fields = array(), $values = array()) {
		return compact('model', 'fields', 'values');
	}

	public function read(Model $model, $queryData = array(), $recursive = NULL) {
		return compact('model', 'queryData');
	}

	public function update(Model $model, $fields = array(), $values = array(), $conditions = NULL) {
		return compact('model', 'fields', 'values');
	}

	public function delete(Model $model, $id = NULL) {
		return compact('model', 'id');
	}
}
