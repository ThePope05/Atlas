<?php

namespace App\Controllers;

use App\Models\WelcomeModel;
use Libraries\Classes\Mvc\Controller;

class WelcomeController extends Controller
{
    public function WelcomePage()
    {
        $this->view("Welcome");
    }
}
