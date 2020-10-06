<?php

$app_path = realpath(__DIR__ . '/..');

include $app_path . '/autoload.php';

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
    'payment',
    '\Website\models\dao\Payment',
    [
        'id' => function () {
            return bin2hex(random_bytes(16));
        },
        'created_at' => function () use ($faker) {
            return $faker->dateTime->format(\Minz\Model::DATETIME_FORMAT);
        },
        'type' => function () use ($faker) {
            return $faker->randomElement(['common_pot', 'subscription']);
        },
        'email' => function () use ($faker) {
            return $faker->email;
        },
        'amount' => function () use ($faker) {
            return $faker->numberBetween(100, 100000);
        },
        'address_first_name' => function () use ($faker) {
            return $faker->firstName;
        },
        'address_last_name' => function () use ($faker) {
            return $faker->lastName;
        },
        'address_address1' => function () use ($faker) {
            return $faker->streetAddress;
        },
        'address_postcode' => function () use ($faker) {
            return $faker->postcode;
        },
        'address_city' => function () use ($faker) {
            return $faker->city;
        },
        'address_country' => function () use ($faker) {
            return $faker->randomElement(\Website\utils\Countries::codes());
        },
    ]
);

\Minz\Tests\DatabaseFactory::addFactory(
    'token',
    '\flusio\models\dao\Token',
    [
        'created_at' => function () use ($faker) {
            return $faker->iso8601;
        },
        'token' => function () {
            return bin2hex(random_bytes(32));
        },
        'expired_at' => function () use ($faker) {
            return $faker->iso8601;
        },
    ]
);

\Minz\Tests\DatabaseFactory::addFactory(
    'account',
    '\Website\models\dao\Account',
    [
        'id' => function () {
            return bin2hex(random_bytes(16));
        },
        'created_at' => function () use ($faker) {
            return $faker->dateTime->format(\Minz\Model::DATETIME_FORMAT);
        },
        'expired_at' => function () use ($faker) {
            return $faker->dateTime->format(\Minz\Model::DATETIME_FORMAT);
        },
        'email' => function () use ($faker) {
            return $faker->email;
        },
        'reminder' => function () use ($faker) {
            return (int)$faker->boolean;
        },
    ]
);
