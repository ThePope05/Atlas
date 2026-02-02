<?php

namespace App\Controllers;

use Libraries\Classes\Mvc\Controller;

class WelcomeController extends Controller
{
    public function WelcomePage($pagetitle)
    {
        $this->View("Welcome", ["pagetitle" => $pagetitle]);
    }
}
