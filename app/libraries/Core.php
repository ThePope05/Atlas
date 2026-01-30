<?php

class Core
{
    private $currentController = 'Homepage';
    private $currentMethod = 'index';
    private $params = [];

    public function __construct()
    {
        $url = $this->getURL();
        if (isset($url[1])) {
            if (file_exists('./app/controllers/' . ucwords($url[1]) . '.php')) {
                $this->currentController = ucwords($url[1]);
                unset($url[1]);
            }
        }
        require_once './app/controllers/' . $this->currentController . '.php';

        $this->currentController = new $this->currentController();


        if (isset($url[2])) {
            if (method_exists($this->currentController, $url[2])) {
                $this->currentMethod = $url[2];
                unset($url[2]);
            }
        }


        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }


    public function getURL()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = rtrim($_SERVER['REQUEST_URI'], '/');

            $url = filter_var($url, FILTER_SANITIZE_URL);

            $url = explode('/', $url);

            unset($url[0]);

            return $url;
        } else {
            return array('Homepage', 'index');
        }
    }
}
