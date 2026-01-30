<?php

class Homepage extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('HomepageModel');
    }

    public function index()
    {
        $data = [
            'title' => 'Homepage'
        ];

        $this->view('Homepage/index', $data);
    }
}
