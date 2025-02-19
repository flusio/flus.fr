<?php

namespace tests\factories;

use Minz\Database;
use Website\models;

/**
 * @extends Database\Factory<models\Token>
 */
class TokenFactory extends Database\Factory
{
    public static function model(): string
    {
        return models\Token::class;
    }

    public static function values(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'token' => function (): string {
                return \Minz\Random::hex(32);
            },

            'created_at' => function () use ($faker) {
                return $faker->dateTime;
            },

            'expired_at' => function () use ($faker) {
                return $faker->dateTime;
            },
        ];
    }
}
