<?php

namespace Libraries\Classes\Mvc;

use Libraries\Constants\Compilable;

abstract class ModuleController
{
    protected string $moduleName = "";

    protected function View(string $viewName, array $data = [])
    {
        $viewEngine = new \Libraries\Classes\FileCompiler\ModuleViewEngine();
        $viewEngine->ModuleName = $this->moduleName;
        $viewEngine->TryGetFile($viewName, $data);
    }
}
