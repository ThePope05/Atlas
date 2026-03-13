<?php

// phpinfo();
// exit;

//This file will be used to check all url data, and validate it
//If valid and no dangers are found, it will send user with the data to the /public/index.php file
//If not valid, it will send the user to the /public/error.php file

//We need to start by making sure the url is valid
include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/libraries/Constants/GlobalFunctions.php';

use Libraries\Classes\FileCompiler\CompileEngine;

$url = $_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);
$file = __DIR__ . $path;
$extension = pathinfo($url, PATHINFO_EXTENSION);

session_start();


if (isset($_SERVER['HTTP_REFERER']) && isset($extension)) {
    // Set the Content-Type header based on the file extension
    if (isset($extension)) {
        switch ($extension) {
            case 'css':
                header('Content-Type: text/css');
                break;
            case 'js':
                header('Content-Type: application/javascript');
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'svg':
                header('Content-Type: image/svg+xml');
                break;
            case 'gif':
                header('Content-Type: image/gif');
                break;
            case 'ico':
                header('Content-Type: image/x-icon');
                break;
            default:
                header('Content-Type: text/html');
                break;
        }
    } else {
        header('Content-Type: text/html');
    }

    // Check if the file exists
    if (is_file($file)) {
        // If the file exists, output its contents
        $compileEngine = new CompileEngine();
        $compileEngine->TryGetFile($path);
        exit();
    } else {
        // If the file does not exist, send a 404 'Not Found' response
        http_response_code(404);
    }
} else {
    // Whitelist: only allow safe URL characters (alphanumeric, slashes, hyphens, underscores, dots, query strings)
    // Reject anything containing encoded or raw angle brackets, null bytes, or other suspicious characters
    $decoded_url = urldecode($url);
    if ($decoded_url !== $url || preg_match('/[<>\'";\x00]/', $decoded_url) || !preg_match('#^[a-zA-Z0-9/_\-\.~:?&=%+,@]+$#', $url)) {
        http_response_code(403);
        exit();
    }

    // Set the maximum number of tokens and the refill rate
    $max_tokens = 100;
    $refill_rate = 1; // tokens per second

    // Initialize the token bucket
    if (!isset($_SESSION['last_request_time'])) {
        $_SESSION['last_request_time'] = time();
        $_SESSION['remaining_tokens'] = $max_tokens;
    }

    // Calculate the number of tokens to add since the last request
    $tokens_to_add = (time() - $_SESSION['last_request_time']) * $refill_rate;
    $_SESSION['remaining_tokens'] = min($_SESSION['remaining_tokens'] + $tokens_to_add, $max_tokens);
    $_SESSION['last_request_time'] = time();

    // Check if there are enough tokens to fulfill the current request
    if ($_SESSION['remaining_tokens'] < 1) {
        // If not, send a 429 'Too Many Requests' response
        http_response_code(429);
        exit('Too Many Requests');
    }

    // Subtract a token for the current request
    $_SESSION['remaining_tokens']--;

    // If we get here, there are enough tokens to fulfill the request
}

require __DIR__ . '/app/index.php';
