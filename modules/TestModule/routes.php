<?php

use Libraries\Classes\Routing\Route;
use Modules\TestModule\Controllers\TestModuleController;

Route::Get('/module-test', [TestModuleController::class, "ModulePage"]);
