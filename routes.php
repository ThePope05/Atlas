<?php

use Libraries\Classes\Routing\Route;
use App\Controllers\TestController;


Route::Get("/welcome", [TestController::class, "Test"]);
Route::Get("/login", [TestController::class, "Login"]);
