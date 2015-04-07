<?php

namespace IntraworQ\Controllers;

class Ajax extends Controller {

	public function index() {
		$this->renderView("ajax");
	}

	public function response() {
		echo json_encode(['response' => 'test ajaxa w kontolerze']);
	}

}
