<?php

$app_path = realpath(__DIR__ . '/..');

include $app_path . '/autoload.php';

$environment = getenv('APP_ENVIRONMENT');
if (!$environment) {
    $environment = 'development';
}

// Get the http information and create a proper Request
$http_method = $_SERVER['REQUEST_METHOD'];
$http_uri = $_SERVER['REQUEST_URI'];
$http_parameters = array_merge(
    $_GET,
    $_POST,
    ['@input' => @file_get_contents('php://input')]
);

\Minz\Configuration::load($environment, $app_path);
\Minz\Environment::initialize();

if (substr($http_uri, 0, 6) === '/admin') {
    \Minz\Environment::startSession();
}

$request = new \Minz\Request($http_method, $http_uri, $http_parameters, $_SERVER);

// Initialize the Application and execute the request to get a Response
$application = new \Website\Application();
$response = $application->run($request);

// make sure to clear Stripe cookies
if (\Minz\Configuration::$environment === 'production') {
    $host = \Minz\Configuration::$url_options['host'];
    setcookie('__stripe_mid', '', time() - 3600, '/', '.' . $host);
    setcookie('__stripe_sid', '', time() - 3600, '/', '.' . $host);
} else {
    setcookie('__stripe_mid', '', time() - 3600);
    setcookie('__stripe_sid', '', time() - 3600);
}

// Generate the HTTP headers and output
http_response_code($response->code());
foreach ($response->headers() as $header) {
    header($header);
}
echo $response->render();
