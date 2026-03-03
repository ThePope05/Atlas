<?php

use Libraries\Classes\Routing\Route;
use App\Controllers\WelcomeController;
use App\Middlewares\TestMiddleware;

Route::Get("/", [WelcomeController::class, "WelcomePage"], [TestMiddleware::class]);
