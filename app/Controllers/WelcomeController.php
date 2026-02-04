<?php

namespace App\Controllers;

use App\Models\WelcomeModel;
use Libraries\Classes\Mvc\Controller;

class WelcomeController extends Controller
{
    protected WelcomeModel $model;

    public function __construct()
    {
        $this->model = new WelcomeModel();
    }

    public function WelcomePage()
    {
        $this->view("Welcome");
    }
}
