<?php

namespace Classes\Mvc;


abstract class Controller
{
    protected function View(string $viewName, array $data = [])
    {
        include_once(__DIR__ . "/../../../public/Views/" .  $viewName . ".php");
    }
}
