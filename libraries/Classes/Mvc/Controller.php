<?php

namespace Libraries\Classes\Mvc;

abstract class Controller
{
    protected Model $model;

    protected function view(string $viewName, array $data = [])
    {
        $viewEngine = new \Libraries\Classes\FileCompiler\ViewEngine();
        $viewEngine->TryGetFile($viewName, $data);
    }
}
