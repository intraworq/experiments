<?php

namespace IntraworQ\Library;

class IwqSlim extends \Slim\Slim {

    protected function mapRoute($args)
    {
        $pattern = array_shift($args);
        $callable = array_pop($args);
        $route = new IwqRoute($pattern, $callable, $this->settings['routes.case_sensitive']);
        $this->router->map($route);
        if (count($args) > 0) {
            $route->setMiddleware($args);
        }

        return $route;
    }

}