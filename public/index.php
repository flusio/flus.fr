<?php

$app_path = realpath(__DIR__ . '/..');

include $app_path . '/autoload.php';

\Minz\Configuration::load('dotenv', $app_path);

$request = \Minz\Request::initFromGlobals();

$application = new \Website\Application();
$response = $application->run($request);
$response->setHeader('Permissions-Policy', 'interest-cohort=()'); // @see https://cleanuptheweb.org/
$response->setHeader('Referrer-Policy', 'same-origin');
$response->setHeader('X-Content-Type-Options', 'nosniff');
$response->setHeader('X-Frame-Options', 'deny');

$plausible_url = \Minz\Configuration::$application['plausible_url'];
if ($plausible_url) {
    $response->addContentSecurityPolicy('connect-src', "'self' {$plausible_url}");
    $response->addContentSecurityPolicy('script-src', "'self' {$plausible_url}");
}

$is_head = strtoupper($_SERVER['REQUEST_METHOD']) === 'HEAD';
\Minz\Response::sendByHttp($response, echo_output: !$is_head);
