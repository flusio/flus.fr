<?php

$app_path = realpath(__DIR__ . '/..');

assert($app_path !== false);

include $app_path . '/vendor/autoload.php';

\Minz\Configuration::load('test', $app_path);

$stripe_endpoint = getenv('STRIPE_ENDPOINT');
if ($stripe_endpoint === false) {
    \Stripe\Stripe::$apiBase = 'http://stripe-mock:12111';
} else {
    \Stripe\Stripe::$apiBase = $stripe_endpoint;
}
