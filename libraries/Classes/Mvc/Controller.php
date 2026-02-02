<?php

namespace Libraries\Classes\Mvc;

abstract class Controller
{
    protected function View(string $viewName, array $data = [])
    {
        $viewEngine = new \Libraries\Classes\FileCompiler\ViewEngine();
        $viewEngine->TryGetFile($viewName, $data);
    }
}
