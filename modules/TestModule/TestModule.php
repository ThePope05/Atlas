<?php

namespace Modules\TestModule;

class TestModule
{
    public static function Register()
    {
        include_once(__DIR__ . "/routes.php");
    }
}
