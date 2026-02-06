<?php

namespace Modules\MODULE_NAME\Controllers;

use Libraries\Classes\Mvc\ModuleController;

class MODULE_NAMEController extends ModuleController
{
    protected string $moduleName = "MODULE_NAME";

    public function ModulePage($pagetitle)
    {
        $this->view("Welcome");
    }
}
