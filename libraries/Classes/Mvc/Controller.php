<?php

namespace Libraries\Classes\Mvc;

abstract class Controller
{
    protected function View(string $viewName, array $data = [])
    {
        $viewEngine = new \Libraries\Classes\ViewCompiler\ViewEngine();
        $viewEngine->render($viewName, $data);
    }
}
