<?php

namespace Libraries\Classes\FileCompiler;

class ViewEngine extends FluxCompileEngine
{
    protected function getReadPath($filename): string
    {
        return __DIR__ . "/../../../public/views/" . $filename . ".fx.php";
    }
}
