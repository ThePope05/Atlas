<?php

function dd($data, $die = true)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";

    if ($die) {
        die();
    }
}

function loggedIn()
{
    return (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false);
}
