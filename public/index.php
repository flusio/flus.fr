<?php

$app_path = realpath(__DIR__ . '/..');

include $app_path . '/autoload.php';

// Get the http information and create a proper Request
$request_method = strtolower($_SERVER['REQUEST_METHOD']);
$http_method = $request_method;
if ($http_method === 'head') {
    $http_method = 'get';
}
$http_uri = $_SERVER['REQUEST_URI'];
$http_parameters = array_merge(
    $_GET,
    $_POST,
    ['@input' => @file_get_contents('php://input')]
);

\Minz\Configuration::load('dotenv', $app_path);
\Minz\Environment::initialize();

if (
    substr($http_uri, 0, 6) === '/admin' ||
    substr($http_uri, 0, 8) === '/account' ||
    substr($http_uri, 0, 6) === '/merci' ||
    substr($http_uri, 0, 11) === '/annulation' ||
    substr($http_uri, 0, 9) === '/invoices'
) {
    \Minz\Environment::startSession();
}

$request = new \Minz\Request($http_method, $http_uri, $http_parameters, $_SERVER);

// Initialize the Application and execute the request to get a Response
$application = new \Website\Application();
$response = $application->run($request);

// Generate the HTTP headers and output
http_response_code($response->code());

foreach ($response->cookies() as $cookie) {
    setcookie($cookie['name'], $cookie['value'], $cookie['options']);
}

foreach ($response->headers() as $header) {
    header($header);
}

if ($request_method !== 'head') {
    echo $response->render();
}
