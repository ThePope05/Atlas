<?php

namespace Classes\Routing;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        Route::$Router = $this;
    }

    public function RegisterRoute(Route $route)
    {
        array_push($this->routes, $route);
    }

    public function LogRoutes()
    {
        foreach ($this->routes as $route) {
            echo $route->Uri . " -> " . $route->Class . "-" . $route->Method . "\n";
        }
    }
}
