<?php

namespace Modules\TestModule\Controllers;

use Libraries\Classes\Mvc\ModuleController;

class TestModuleController extends ModuleController
{
    protected string $moduleName = "TestModule";

    public function ModulePage($pagetitle)
    {
        $this->view("Welcome");
    }
}
