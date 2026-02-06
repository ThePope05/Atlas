<?php

use Libraries\Classes\Routing\Route;
use Modules\MODULE_NAME\Controllers\MODULE_NAMEController;

Route::Get('/module-test', [MODULE_NAMEController::class, "ModulePage"]);
