<?php

use Libraries\Classes\Routing\Route;
use Modules\ModuleTest\Controllers\ModuleTestController;

Route::Get('/module-test', [ModuleTestController::class, "ModulePage"]);
