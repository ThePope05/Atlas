<?php

use Libraries\Classes\Routing\Route;
use app\controllers\TestController;


Route::Get("/welcome", [TestController::class, "Test"]);
Route::Get("/login", [TestController::class, "Login"]);
