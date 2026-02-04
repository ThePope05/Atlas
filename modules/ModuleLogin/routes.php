<?php

use Libraries\Classes\Routing\Route;
use Modules\ModuleLogin\Controllers\ModuleLoginController;

Route::Get('/login', [ModuleLoginController::class, "LoginPage"]);
Route::Post('/login', [ModuleLoginController::class, "Login"]);

Route::Get('/register', [ModuleLoginController::class, "RegisterPage"]);
Route::Post('/register', [ModuleLoginController::class, "Register"]);
