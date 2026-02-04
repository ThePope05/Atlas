<?php

namespace Modules\ModuleLogin\Controllers;

use Libraries\Classes\Mvc\ModuleController;
use Modules\ModuleLogin\Models\ModuleLoginModel;

class ModuleLoginController extends ModuleController
{
    protected string $moduleName = "ModuleLogin";
    protected ModuleLoginModel $model;

    public function __construct()
    {
        $this->model = new ModuleLoginModel();
    }

    public function LoginPage()
    {
        $this->view("Login");
    }

    public function Login()
    {
        $email = $_POST["email"];
        $password = $_POST["password"];

        if ($this->model->Login($email, $password))
            $this->openUrl('/welcome');
        else
            $this->openUrl('/login');
    }


    public function RegisterPage()
    {
        $this->view("Register");
    }

    public function Register()
    {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $this->model->Register($email, $password);

        if ($this->model->Login($email, $password))
            $this->openUrl('/welcome');
        else
            $this->openUrl('/login');
    }
}
