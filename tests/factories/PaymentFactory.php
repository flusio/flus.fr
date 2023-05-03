<?php

namespace tests\factories;

use Minz\Database;
use Website\models;

class PaymentFactory extends Database\Factory
{
    public static function model(): string
    {
        return models\Payment::class;
    }

    public static function values(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'id' => function () {
                return \Minz\Random::hex(32);
            },

            'created_at' => function () use ($faker) {
                return $faker->dateTime;
            },

            'is_paid' => function () use ($faker) {
                return $faker->boolean;
            },

            'type' => function () use ($faker) {
                return $faker->randomElement(['common_pot', 'subscription']);
            },

            'account_id' => function () {
                return AccountFactory::create()->id;
            },

            'amount' => function () use ($faker) {
                return $faker->numberBetween(100, 100000);
            },
        ];
    }
}
