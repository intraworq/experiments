<?php namespace IntraworQ\Controllers;

class Main extends Controller {
	
	public function index() {
		$this->app->session->add('imie','michal');
		$this->app->flash('notice','Przyklad komunikatu flash. Sesja zawiera "imie" : '.$this->app->session->get('imie'));
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