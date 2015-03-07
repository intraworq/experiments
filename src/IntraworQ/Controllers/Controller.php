<?php namespace IntraworQ\Controllers;

abstract class Controller extends \Slim\Slim
{
	protected $model;
	protected $view;
	protected $settings;
	protected $app;

	public function __construct(\Slim\Slim $app, \PlanQ\Models\Model $model = null, array $settings = array()) {    
		parent::__construct($settings);
		$this->model = $model;
		$this->app = $app;
		$this->view = $app->view();
		$this->view->set('model', $this->model);
	}

	public function renderView($name, $extension = '.tpl', $status = null) {
		$this->app->render($name . $extension, array(), $status);
	}
}