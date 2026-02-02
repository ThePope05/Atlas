<?php

use Libraries\Classes\Routing\Route;
use App\Controllers\WelcomeController;


Route::Get("/welcome", [WelcomeController::class, "WelcomePage"]);
