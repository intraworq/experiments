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
	 * @see \Slim\Router::map()
	 * 
	 * @param \Slim\Route $route
	 */
	public function map(\Slim\Route $route) {
		list($groupPattern, $groupMiddleware) = $this->processGroups();
		if (!$this->container->get('App')->acl->hasResource($groupPattern . $route->getPattern())) {
			$this->container->get('App')->acl->addResource($groupPattern . $route->getPattern());
		}
		$route->setPattern($groupPattern . $route->getPattern());
		$route->setContainer($this->container);
		$this->routes[] = $route;
		foreach ($groupMiddleware as $middleware) {
			$route->setMiddleware($middleware);
		}
	}

	/**
	 *
	 * @return \DI\Container
	 */
	public function getContainer() {
		return $this->container;
	}

}
