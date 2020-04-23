<?php

$app_path = realpath(__DIR__ . '/..');

include $app_path . '/autoload.php';
include $app_path . '/tests/utils.php';

\Minz\Configuration::load('test', $app_path);
\Minz\Environment::initialize();
\Minz\Environment::startSession();

\Stripe\Stripe::$apiBase = 'http://localhost:12111';

include($app_path . '/lib/Faker/src/autoload.php');

$faker = \Faker\Factory::create();

$faker_seed = getenv('SEED');
if ($faker_seed) {
    $faker_seed = intval($faker_seed);
} else {
    $faker_seed = random_int(PHP_INT_MIN, PHP_INT_MAX);
}

$faker->seed($faker_seed);
echo 'Use SEED=' . $faker_seed . " to reproduce this suite.\n";

// Initialize factories
\Minz\Tests\DatabaseFactory::addFactory(
    'payments',
    '\Website\models\dao\Payment',
    [
        'id' => function () {
            return bin2hex(random_bytes(16));
        },
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
            return $faker->numberBetween(100, 100000);
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
