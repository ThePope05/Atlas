<?php

namespace Modules\ModuleTest\Controllers;

use Libraries\Classes\Mvc\ModuleController;

class ModuleTestController extends ModuleController
{
    public string $moduleName = "ModuleTest";

    public function ModulePage($pagetitle)
    {
        $this->View("Welcome");
    }
}
