<?php

namespace Libraries\Classes\Mvc;

abstract class Controller
{
    protected function view(string $viewName, array $data = [])
    {
        $viewEngine = new \Libraries\Classes\FileCompiler\ViewEngine();
        $viewEngine->TryGetFile($viewName, $data);
    }

    protected function redirect(string $url)
    {
        // Only allow local redirects (paths starting with /) to prevent open redirect attacks.
        // Block protocol-relative URLs (//evil.com) and absolute URLs with a scheme.
        if (!str_starts_with($url, '/') || str_starts_with($url, '//')) {
            throw new \InvalidArgumentException("Redirect to external URLs is not allowed");
        }

        header("Location: $url");
    }
}
