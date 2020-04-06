<?php

$app_path = realpath(__DIR__ . '/..');

include $app_path . '/autoload.php';

\Minz\Configuration::load('test', $app_path);
\Minz\Environment::initialize();

\Stripe\Stripe::$apiBase = 'http://localhost:12111';

include($app_path . '/lib/Faker/src/autoload.php');

$faker = \Faker\Factory::create();
$faker_seed = random_int(PHP_INT_MIN, PHP_INT_MAX);

// To force the seed, uncomment the next line and set the seed given by the
// tests suite.
//$faker_seed = -8858127975353186437;

$faker->seed($faker_seed);
echo 'Faker seed: ' . $faker_seed . "\n";
