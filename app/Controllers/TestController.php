<?php

namespace app\controllers;

use Classes\Mvc\Controller;

class TestController extends Controller
{
    public function Test($pagetitle)
    {
        $this->View("Welcome", ["pagetitle" => $pagetitle]);
    }
}
