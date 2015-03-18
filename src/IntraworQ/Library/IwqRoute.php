<?php

namespace IntraworQ\Library;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class IwqRoute extends \Slim\Route {

	/**
	 *
	 * @var \DI\Container
	 */
	private $container;

	public function setContainer(\DI\Container $container) {
		$this->container = $container;
	}

    public function setCallable($callable)
    {
        $matches = array();
        if (is_string($callable) && preg_match('!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!', $callable, $matches)) {
            $class = $matches[1];
            $method = $matches[2];
            $callable = function() use ($class, $method) {
                static $obj = null;
                if ($obj === null) {
                    $obj = $this->container->get($class);
//                    $obj = new $class;
                }
                return call_user_func_array(array($obj, $method), func_get_args());
            };
        }

        if (!is_callable($callable)) {
            throw new \InvalidArgumentException('Route callable must be callable');
        }

        $this->callable = $callable;
    }
}
