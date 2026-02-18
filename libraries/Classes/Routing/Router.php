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
            $routeUri = strtolower($route->Uri);
            $lowerUri = strtolower($uri);

            // Exact match, or the URI continues with a '/' (segment boundary)
            $isMatch = $lowerUri === $routeUri
                || str_starts_with($lowerUri, $routeUri . '/');

            if ($isMatch && $route->Action == $action) {
                $extra = trim(substr($uri, strlen($route->Uri)), '/');
                $params = $extra !== '' ? explode('/', $extra) : [];

                $class = new $route->Class();
                call_user_func_array([$class, $route->Method], $params);
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
            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $url = rtrim($url, '/');

            return $url ?: '/';
        } else {
            return '/';
        }
    }
}
