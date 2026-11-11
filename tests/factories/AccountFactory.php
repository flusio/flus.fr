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
            'id' => fn(): string => \Minz\Random::hex(32),

            'created_at' => fn() => $faker->dateTime,

            'expired_at' => fn() => $faker->dateTime,

            'preferred_service' => fn() => $faker->randomElement(['flus', 'freshrss']),

            'email' => fn() => $faker->email,

            'reminder' => fn() => $faker->boolean,

            'entity_type' => fn() => $faker->randomElement(['natural', 'legal']),

            'address_country' => fn() => $faker->randomElement(\Website\utils\Countries::codes()),
        ];
    }
}
