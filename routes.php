<?php

include "libraries\Classes\Routing\Route.php";
include "libraries\Classes\Routing\Router.php";
include "libraries\Constants\Enums.php";

use Classes\Routing\Router;
use Classes\Routing\Route;

$router = new Router();

Route::Get("/", [Router::class, "TestMethod"]);
Route::Get("/1", [Router::class, "TestMethod1"]);

$router->LogRoutes();
