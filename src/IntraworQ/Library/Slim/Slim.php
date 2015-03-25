<?php

namespace IntraworQ\Library\Slim;

class Slim extends \Slim\Slim {

	/**
	 * @see \Slim\Slim::mapRoute()
	 *
	 * @param   array (See notes above)
	 * @return  \Slim\Route
	 */
	protected function mapRoute($args) {
		$pattern = array_shift($args);
		$callable = array_pop($args);
		if (!$this->acl->hasResource($pattern)) {
			$this->acl->addResource($pattern);
		}
		$route = new Route($pattern, $callable, $this->settings['routes.case_sensitive']);
		$this->router->map($route);
		if (count($args) > 0) {
			$route->setMiddleware($args);
		}
		return $route;
	}

}
