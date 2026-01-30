
<?php

class CONTROLLER_NAME extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('CONTROLLER_NAMEModel');
    }

    public function index()
    {
        $data = [
            'title' => 'CONTROLLER_NAME'
        ];

        $this->view('CONTROLLER_NAME/index', $data);
    }
}
