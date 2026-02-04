<?php

namespace Libraries\Classes\Routing;

use Libraries\Constants\RouteActions;

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
            echo $route->Uri . " -> " . $route->Class . "::" . $route->Method . "\n";
        }
    }

    public function ProcessUri()
    {
        $uri = $this->GetUri();

        $action = null;
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $action = RouteActions::Post;
                break;
            default:
                $action = RouteActions::Get;
                break;
        }

        foreach ($this->routes as $route) {
            if (str_starts_with($uri, $route->Uri) && $route->Action == $action) {

                $class = new $route->Class();
                call_user_func_array(
                    [$class, $route->Method],
                    explode('/', trim(str_replace($route->Uri, "", $uri), '/'))
                );
                return;
            }
        }

        $viewEngine = new \Libraries\Classes\FileCompiler\ViewEngine();
        $viewEngine->TryGetFile("forbiddenpage");
        http_response_code(404);
    }

    public function GetUri()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = rtrim($_SERVER['REQUEST_URI'], '/');

            $url = filter_var($url, FILTER_SANITIZE_URL);

            $url = urldecode($url);

            return $url;
        } else {
            return array('Homepage', 'index');
        }
    }
}
