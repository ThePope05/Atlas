<?php

namespace Libraries\Classes\Routing;

abstract class Middleware
{
    abstract public function Handle(): bool;

    public function OnDeny(): void
    {
        http_response_code(403);
        $viewEngine = new \Libraries\Classes\FileCompiler\ViewEngine();
        $viewEngine->TryGetFile(
            "ErrorPage",
            [
                "msg" => "Oops, you're not allowed to view this page!",
                "code" => "403"
            ]
        );

        exit;
    }
}
