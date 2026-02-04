<?php

function dd(mixed $values, bool $die = true)
{
    echo '<pre>';
    var_dump($values);
    echo '</pre>';

    if ($die)
        exit();
}
