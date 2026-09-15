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
            'id' => function (): string {
                return \Minz\Random::hex(32);
            },

            'created_at' => function () use ($faker) {
                return $faker->dateTime;
            },

            'email' => function () use ($faker) {
                return $faker->email;
            },

            'organisation' => function () use ($faker) {
                return $faker->company;
            },
        ];
    }
}
