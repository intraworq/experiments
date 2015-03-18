<?php namespace IntraworQ\Library;

class IwqRouter extends \Slim\Router {

	private $conainer;

	public function __construct(\DI\Container $container) {
		$this->conainer = $container;
		parent::__construct();
	}

    public function map(\Slim\Route $route)
    {
        list($groupPattern, $groupMiddleware) = $this->processGroups();

        $route->setPattern($groupPattern . $route->getPattern());
		$route->setContainer($this->conainer);

		$this->routes[] = $route;


        foreach ($groupMiddleware as $middleware) {
            $route->setMiddleware($middleware);
        }
    }

}