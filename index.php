<?php
include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/config/config.php';

use Libraries\Classes\Routing\Router;

$router = new Router();

require "./routes.php";

$router->ProcessUri();
