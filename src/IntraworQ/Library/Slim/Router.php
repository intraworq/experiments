<?php

namespace IntraworQ\Library\Slim;

class Router extends \Slim\Router {

	/**
	 *
	 * @var \DI\Container
	 */
	protected $container;

	/**
	 * Constructor
	 */
	public function __construct(\DI\Container $container) {
		parent::__construct();
		$this->container = $container;
	}

	/**
	 *
	 * @return \DI\Container
	 */
	function getContainer() {
		return $this->container;
	}

}
