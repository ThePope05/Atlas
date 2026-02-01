<?php

namespace App\Controllers;

use Libraries\Classes\Mvc\Controller;

class TestController extends Controller
{
    public function Test($pagetitle)
    {
        $this->View("Welcome", ["pagetitle" => $pagetitle]);
    }

    public function Login($pagetitle)
    {
        $this->View("login/Login");
    }
}
