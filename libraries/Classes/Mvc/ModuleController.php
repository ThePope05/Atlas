<?php

namespace Libraries\Classes\Mvc;

use Libraries\Constants\Compilable;

abstract class ModuleController
{
    protected string $moduleName = "";

    protected function View(string $viewName, array $data = [])
    {
        $viewEngine = new \Libraries\Classes\ViewCompiler\ViewEngine();
        $viewEngine->render($viewName, $data, Compilable::ModuleView, $this->moduleName);
    }
}
