<?php

namespace IntraworQ\Controllers;

abstract class BaseController {

	public $app;

	public function __construct(\IntraworQ\Library\IwqSlim $app) {
		$this->app = $app;
	}
}
