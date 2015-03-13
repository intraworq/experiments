<?php namespace IntraworQ\Controllers;

class Main extends Controller {
	
	public function index() {

		$this->renderView("index");
	}

	public function _404() {
		$env = \Slim\Environment::getInstance();
		$request = new \Slim\Http\Request($env);
		$uri = $request->getResourceUri();
		$this->view()->set('uri', $uri);
		$this->renderView('404');
	}
}