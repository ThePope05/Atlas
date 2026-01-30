<?php

class BaseController
{

    public function view($view, $data = []): void
    {
        if (file_exists('./app/views/' . $view . '.php')) {
            require_once('./app/views/' . $view . '.php');
        } else {
            echo 'This view does not exist';
        }
    }

    public function model($model): object|null
    {
        if (file_exists('./app/models/' . $model . '.php')) {
            require_once './app/models/' . $model . '.php';
            return new $model();
        } else {
            echo 'This model does not exist';
            return null;
        }
    }

    public function component($component, $componentData = []): void
    {
        if (file_exists('./app/views/components/' . $component . '.php')) {
            require './app/views/components/' . $component . '.php';
        } else {
            echo 'This component does not exist';
        }
    }
}
