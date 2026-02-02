<?php

namespace Libraries\Classes\FileCompiler;

use Exception;

class ModuleViewEngine extends ModuleFluxCompileEngine
{
    protected function getReadPath($filename): string
    {
        if ($this->ModuleName == "" || $this->ModuleName == null)
            throw new Exception("Module name not set");

        return __DIR__ . "/../../../modules/" . $this->ModuleName . "/views/" . $filename . ".fx.php";
    }
}
