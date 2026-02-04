<?php

namespace Libraries\Classes\Mvc;

use Exception;
use Libraries\Constants\Compilable;

abstract class ModuleController extends Controller
{
    protected string $moduleName = "";

    protected function view(string $viewName, array $data = [])
    {
        $viewEngine = new \Libraries\Classes\FileCompiler\ModuleViewEngine();
        $viewEngine->ModuleName = $this->moduleName;
        $viewEngine->TryGetFile($viewName, $data);
    }
}
