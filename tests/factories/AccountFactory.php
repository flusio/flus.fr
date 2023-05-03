<?php

namespace tests\factories;

use Minz\Database;
use Website\models;

class AccountFactory extends Database\Factory
{
    public static function model(): string
    {
        return models\Account::class;
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

            'expired_at' => function () use ($faker) {
                return $faker->dateTime;
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
                return $faker->boolean;
            },

            'address_country' => function () use ($faker) {
                return $faker->randomElement(\Website\utils\Countries::codes());
            },
        ];
    }
}
