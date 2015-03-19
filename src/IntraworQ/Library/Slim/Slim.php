<?php

namespace IntraworQ\Library\Slim;

class Slim extends \Slim\Slim {
	/*	 * ******************************************************************************
	 * Routing
	 * ***************************************************************************** */

	/**
	 * Add GET|POST|PUT|PATCH|DELETE route
	 *
	 * Adds a new route to the router with associated callable. This
	 * route will only be invoked when the HTTP request's method matches
	 * this route's method.
	 *
	 * ARGUMENTS:
	 *
	 * First:       string  The URL pattern (REQUIRED)
	 * In-Between:  mixed   Anything that returns TRUE for `is_callable` (OPTIONAL)
	 * Last:        mixed   Anything that returns TRUE for `is_callable` (REQUIRED)
	 *
	 * The first argument is required and must always be the
	 * route pattern (ie. '/books/:id').
	 *
	 * The last argument is required and must always be the callable object
	 * to be invoked when the route matches an HTTP request.
	 *
	 * You may also provide an unlimited number of in-between arguments;
	 * each interior argument must be callable and will be invoked in the
	 * order specified before the route's callable is invoked.
	 *
	 * USAGE:
	 *
	 * Slim::get('/foo'[, middleware, middleware, ...], callable);
	 *
	 * @param   array (See notes above)
	 * @return  \Slim\Route
	 */
	protected function mapRoute($args) {
		$pattern = array_shift($args);
		$callable = array_pop($args);
		$route = new Route($pattern, $callable, $this->settings['routes.case_sensitive']);
		$this->router->map($route);
		if (count($args) > 0) {
			$route->setMiddleware($args);
		}

		return $route;
	}
    /**
	 * Call
	 *
	 * This method finds and iterates all route objects that match the current request URI.
	 */
	public function call() {
		try {
			if (isset($this->environment['slim.flash'])) {
				$this->view()->setData('flash', $this->environment['slim.flash']);
			}
			$this->applyHook('slim.before');
			ob_start();
			$this->applyHook('slim.before.router');
			$dispatched = false;
			$matchedRoutes = $this->router->getMatchedRoutes($this->request->getMethod(), $this->request->getResourceUri());
			foreach ($matchedRoutes as $route) {
				try {
					$con = $this->router->getContainer();
					$route->setContainer($con);
					$this->applyHook('slim.before.dispatch');
					$dispatched = $route->dispatch($con);
					$this->applyHook('slim.after.dispatch');
					if ($dispatched) {
						break;
					}
				} catch (\Slim\Exception\Pass $e) {
					continue;
				}
			}
			if (!$dispatched) {
				$this->notFound();
			}
			$this->applyHook('slim.after.router');
			$this->stop();
		} catch (\Slim\Exception\Stop $e) {
			$this->response()->write(ob_get_clean());
		} catch (\Exception $e) {
			if ($this->config('debug')) {
				throw $e;
			} else {
				try {
					$this->error($e);
				} catch (\Slim\Exception\Stop $e) {
					// Do nothing
				}
			}
		}
	}

}
