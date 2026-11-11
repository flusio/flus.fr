<?php

namespace tests\factories;

use Minz\Database;
use Website\models;

/**
 * @extends Database\Factory<models\ProRegistration>
 */
class ProRegistrationFactory extends Database\Factory
{
    public static function model(): string
    {
        return models\ProRegistration::class;
    }

    public static function values(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'id' => fn(): string => \Minz\Random::hex(32),

            'created_at' => fn() => $faker->dateTime,

            'email' => fn() => $faker->email,

            'organisation' => fn() => $faker->company,
        ];
    }
}
