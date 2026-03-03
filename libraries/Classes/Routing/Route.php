<?php

namespace Libraries\Classes\Routing;

use Libraries\Constants\RouteActions;

class Route
{
    public static Router $Router;
    public RouteActions $Action;
    public string $Uri = "";
    public string $Class = "";
    public string $Method = "";
    public array $Middlewares = [];

    public function __construct(RouteActions $action, string $uri, string $class_name, string $method_name, array $middlewares = [])
    {
        $this->Action = $action;
        $this->Uri = $uri;
        $this->Class = $class_name;
        $this->Method = $method_name;
        $this->Middlewares = $middlewares;

        Route::$Router->RegisterRoute($this);
    }

    /**
     * Run all middlewares assigned to this route.
     * Returns true if all pass, false if any deny the request.
     */
    public function RunMiddlewares(): bool
    {
        foreach ($this->Middlewares as $middleware_class) {
            $instance = new $middleware_class();

            if (!$instance->Handle()) {
                $instance->OnDeny();
                return false;
            }
        }

        return true;
    }

    public static function Get(string $uri, array $access, array $middlewares = [])
    {
        $class_name = $access[0];
        $method_name = $access[1];
        new Route(RouteActions::Get, $uri, $class_name, $method_name, $middlewares);
    }

    public static function Post(string $uri, array $access, array $middlewares = [])
    {
        $class_name = $access[0];
        $method_name = $access[1];
        new Route(RouteActions::Post, $uri, $class_name, $method_name, $middlewares);
    }
}
