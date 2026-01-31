<?php

use Classes\Routing\Route;
use app\controllers\TestController;


Route::Get("/welcome", [TestController::class, "Test"]);
