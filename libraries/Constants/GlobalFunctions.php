<?php

function dd(mixed $values, bool $die = true)
{
    echo '<pre>';
    var_dump($values);
    echo '</pre>';

    if ($die)
        exit();
}

function isRoute(string $route): bool
{
    $current = strtolower(rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') ?: '/');
    return $current === strtolower(rtrim($route, '/') ?: '/');
}
