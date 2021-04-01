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
        'is_paid' => function () use ($faker) {
            return (int)$faker->boolean;
        },
        'type' => function () use ($faker) {
            return $faker->randomElement(['common_pot', 'subscription']);
        },
        'account_id' => function () use ($faker) {
            $account_factory = new \Minz\Tests\DatabaseFactory('account');
            return $account_factory->create();
        },
        'amount' => function () use ($faker) {
            return $faker->numberBetween(100, 100000);
        },
    ]
);

\Minz\Tests\DatabaseFactory::addFactory(
    'credit',
    '\Website\models\dao\Payment',
    [
        'id' => function () {
            return bin2hex(random_bytes(16));
        },
        'created_at' => function () use ($faker) {
            return $faker->dateTime->format(\Minz\Model::DATETIME_FORMAT);
        },
        'is_paid' => function () use ($faker) {
            return (int)$faker->boolean;
        },
        'type' => function () use ($faker) {
            return 'credit';
        },
        'account_id' => function () use ($faker) {
            $account_factory = new \Minz\Tests\DatabaseFactory('account');
            return $account_factory->create();
        },
        'amount' => function () use ($faker) {
            return $faker->numberBetween(100, 100000);
        },
        'credited_payment_id' => function () {
            $payment_factory = new \Minz\Tests\DatabaseFactory('payment');
            return $payment_factory->create();
        },
    ]
);

\Minz\Tests\DatabaseFactory::addFactory(
    'pot_usage',
    '\Website\models\dao\PotUsage',
    [
        'id' => function () {
            return bin2hex(random_bytes(16));
        },
        'created_at' => function () use ($faker) {
            return $faker->dateTime->format(\Minz\Model::DATETIME_FORMAT);
        },
        'amount' => function () use ($faker) {
            return $faker->numberBetween(100, 100000);
        },
        'frequency' => function () use ($faker) {
            return $faker->randomElement(['month', 'year']);
        },

        // a common pot payment is always completed
        'completed_at' => function () use ($faker) {
            return $faker->dateTime->format(\Minz\Model::DATETIME_FORMAT);
        },
        'is_paid' => function () {
            return true;
        },
    ]
);

\Minz\Tests\DatabaseFactory::addFactory(
    'token',
    '\Website\models\dao\Token',
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
        'preferred_frequency' => function () use ($faker) {
            return $faker->randomElement(['month', 'year']);
        },
        'preferred_payment_type' => function () use ($faker) {
            return $faker->randomElement(['common_pot', 'card']);
        },
        'preferred_service' => function () use ($faker) {
            return $faker->randomElement(['flusio', 'freshrss']);
        },
        'email' => function () use ($faker) {
            return $faker->email;
        },
        'reminder' => function () use ($faker) {
            return (int)$faker->boolean;
        },
        'address_country' => function () use ($faker) {
            return $faker->randomElement(\Website\utils\Countries::codes());
        },
    ]
);
