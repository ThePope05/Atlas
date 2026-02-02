<?php

namespace Libraries\Classes\FileCompiler;

class ComponentEngine extends FluxCompileEngine
{
    protected function getReadPath($filename): string
    {
        return __DIR__ . "/../../../public/components/" . $filename . ".fx.php";
    }
}
