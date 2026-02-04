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

    public function __construct(RouteActions $action, string $uri, string $class_name, string $method_name)
    {
        $this->Action = $action;
        $this->Uri = $uri;
        $this->Class = $class_name;
        $this->Method = $method_name;

        Route::$Router->RegisterRoute($this);
    }

    public static function Get(string $uri, array $access)
    {
        $class_name = $access[0];
        $method_name = $access[1];
        new Route(RouteActions::Get, $uri, $class_name, $method_name);
    }

    public static function Post(string $uri, array $access)
    {
        $class_name = $access[0];
        $method_name = $access[1];
        new Route(RouteActions::Post, $uri, $class_name, $method_name);
    }
}
