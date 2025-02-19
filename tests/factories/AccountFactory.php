<?php

namespace tests\factories;

use Minz\Database;
use Website\models;

/**
 * @extends Database\Factory<models\Account>
 */
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
            'id' => function (): string {
                return \Minz\Random::hex(32);
            },

            'created_at' => function () use ($faker) {
                return $faker->dateTime;
            },

            'expired_at' => function () use ($faker) {
                return $faker->dateTime;
            },

            'preferred_service' => function () use ($faker) {
                return $faker->randomElement(['flus', 'freshrss']);
            },

            'email' => function () use ($faker) {
                return $faker->email;
            },

            'reminder' => function () use ($faker) {
                return $faker->boolean;
            },

            'entity_type' => function () use ($faker) {
                return $faker->randomElement(['natural', 'legal']);
            },

            'address_country' => function () use ($faker) {
                return $faker->randomElement(\Website\utils\Countries::codes());
            },
        ];
    }
}
