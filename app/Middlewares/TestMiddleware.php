<?php

namespace App\Middlewares;

use Libraries\Classes\Routing\Middleware;

class TestMiddleware extends Middleware
{
    public function Handle(): bool
    {
        return true;
    }
}
