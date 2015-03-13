<?php

namespace IntraworQ\Controllers;


abstract class BaseController {

	public $app;

	public function __construct() {
		$this->app = \Slim\Slim::getInstance();
	}

}

