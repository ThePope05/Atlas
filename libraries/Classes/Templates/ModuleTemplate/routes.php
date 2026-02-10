<?php

use Libraries\Classes\Routing\Route;
use Modules\MODULE_NAME\Controllers\MODULE_NAMEController;

Route::Get('/MODULE_NAME', [MODULE_NAMEController::class, "Index"]);
