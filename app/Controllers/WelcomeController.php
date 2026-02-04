<?php

namespace App\Controllers;

use App\Models\WelcomeModel;
use Libraries\Classes\Mvc\Controller;

class WelcomeController extends Controller
{
    public function __construct()
    {
        $this->model = new WelcomeModel();
    }

    public function WelcomePage()
    {
        $allReservations = $this->model->GetAll();
        //dd($allReservations);
        $this->view("Welcome", ["reservations" => $allReservations]);
    }
}
