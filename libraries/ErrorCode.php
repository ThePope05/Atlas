<?php

set_error_handler("customError");

function customError($errno, $errstr, $errfile, $errline)
{
    $allErrFileParts = explode('\\', $errfile);
    $errfile = $allErrFileParts[count($allErrFileParts) - 2] . '/' . $allErrFileParts[count($allErrFileParts) - 1];

    $errorEl = "<div style='display: flex; color: white; align-items: center; background-color: #313131; border-radius: 1rem; margin: 1rem; width: 40rem; font-family: sans-serif; font-weight: 900;'>";
    $errorEl .= "<span style='padding-left: .5rem; color: orange; width: max-content; height: 100%; font-size: 5rem;'>L:$errline</span>";
    $errorEl .= "<div style='display: flex; flex-direction: column; width: ; height: 100%; padding: 0 .5rem;'>";
    $errorEl .= "<span style='width: 100%; height: 50%; font-size: 1rem; color: red;'>$errstr</span>";
    $errorEl .= "<span style='width: 100%; height: 50%; font-size: 1rem; color: orange;'>$errfile</span>";
    $errorEl .= "</div> </div>";

    echo $errorEl;
}
