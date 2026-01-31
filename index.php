<?php
include __DIR__ . '/vendor/autoload.php';

use Classes\Routing\Router;


$router = new Router();

require "./routes.php";

$router->ProcessUri();
