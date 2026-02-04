<?php

namespace Libraries\Classes\Mvc;

abstract class Controller
{
    protected function view(string $viewName, array $data = [])
    {
        $viewEngine = new \Libraries\Classes\FileCompiler\ViewEngine();
        $viewEngine->TryGetFile($viewName, $data);
    }

    protected function openUrl(string $url)
    {
        header("Location: $url");
    }
}
