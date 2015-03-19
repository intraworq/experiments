<?php
namespace IntraworQ\Library\Slim;

class Route extends \Slim\Route {

	private $container;
   /**
	 * Set route callable
	 * @param  mixed $callable
	 * @throws \InvalidArgumentException If argument is not callable
	 */
	public function setCallable($callable) {
		$matches = array();
		if (is_string($callable) && preg_match('!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!', $callable, $matches)) {
			$class = $matches[1];
			$method = $matches[2];
			$callable = function() use ($class, $method) {
				static $obj = null;
				if ($obj === null) {

					/* @var $con \DI\Container */
					$con = $this->container;
					$obj = $con->get($class);
				}

				return call_user_func_array(array($obj, $method), func_get_args());
			};
		}
		if (!is_callable($callable)) {
			throw new \InvalidArgumentException('Route callable must be callable');
		}

		$this->callable = $callable;
	}

	/**
	 *
	 * @param \DI\Container $container
	 */
	public function setContainer(\DI\Container $container) {
		$this->container = $container;
	}

}
