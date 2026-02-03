<?php

namespace App\Controllers;

use Libraries\Classes\Mvc\Controller;

class WelcomeController extends Controller
{
    public function WelcomePage($pagetitle)
    {
        $this->view("Welcome", ["pagetitle" => $pagetitle]);
    }
}
