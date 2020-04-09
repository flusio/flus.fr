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

// Initialize factories
\Minz\Tests\DatabaseFactory::addFactory(
    'payments',
    '\Website\models\dao\Payment',
    [
        'created_at' => function () {
            $faker = \Faker\Factory::create();
            return $faker->unixTime;
        },
        'type' => function () {
            $faker = \Faker\Factory::create();
            return $faker->randomElement(['common_pot', 'subscription']);
        },
        'email' => function () {
            $faker = \Faker\Factory::create();
            return $faker->email;
        },
        'amount' => function () {
            $faker = \Faker\Factory::create();
            return $faker->numberBetween(1, 1000);
        },
        'address_first_name' => function () {
            $faker = \Faker\Factory::create();
            return $faker->firstName;
        },
        'address_last_name' => function () {
            $faker = \Faker\Factory::create();
            return $faker->lastName;
        },
        'address_address1' => function () {
            $faker = \Faker\Factory::create();
            return $faker->streetAddress;
        },
        'address_postcode' => function () {
            $faker = \Faker\Factory::create();
            return $faker->postcode;
        },
        'address_city' => function () {
            $faker = \Faker\Factory::create();
            return $faker->city;
        },
    ]
);
