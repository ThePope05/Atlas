<?php
include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../config/config.php';

use Libraries\Classes\ModuleLoader\ModuleEngine;
use Libraries\Classes\Routing\Router;

$router = new Router();
$moduleLoader = new ModuleEngine();

require "./routes.php";

$moduleLoader->LoadModules();

$router->ProcessUri();
