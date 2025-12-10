<?php

$app_path = realpath(__DIR__ . '/..');

assert($app_path !== false);

include $app_path . '/vendor/autoload.php';

\Minz\Configuration::load('test', $app_path);

// Initialize the database
\Minz\Database::reset();
$schema = @file_get_contents(\Minz\Configuration::$schema_path);

assert($schema !== false);

$database = \Minz\Database::get();
$database->exec($schema);

// Set Stripe test endpoint
$stripe_endpoint = getenv('STRIPE_ENDPOINT');
if ($stripe_endpoint === false) {
    \Stripe\Stripe::$apiBase = 'http://stripe-mock:12111';
} else {
    \Stripe\Stripe::$apiBase = $stripe_endpoint;
}
