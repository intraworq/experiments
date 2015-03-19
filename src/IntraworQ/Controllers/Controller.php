<?php namespace IntraworQ\Controllers;

abstract class Controller 
{
	protected $model;
	protected $view;
	protected $settings;
	protected $app;

	public function __construct(\Slim\Slim $app, \IntraworQ\Models\Model $model = null, array $settings = array()) {
		$this->model = $model;
		$this->app = $app;
		$this->view = $app->view();
		$this->view->set('model', $this->model);
	}

	public function renderView($name, $extension = '.tpl', $params = array(), $status = null) {
		$params = array_merge(['name' => $name],$params);
		$this->app->render($name . $extension,
			$params, $status);
	}
}