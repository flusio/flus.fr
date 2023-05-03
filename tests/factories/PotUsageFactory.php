<?php

namespace tests\factories;

use Minz\Database;
use Website\models;

class PotUsageFactory extends Database\Factory
{
    public static function model(): string
    {
        return models\PotUsage::class;
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

            'account_id' => function () {
                return AccountFactory::create()->id;
            },

            'amount' => function () use ($faker) {
                return $faker->numberBetween(100, 100000);
            },

            'frequency' => function () use ($faker) {
                return $faker->randomElement(['month', 'year']);
            },

            // a common pot payment is always completed
            'completed_at' => function () use ($faker) {
                return $faker->dateTime;
            },

            'is_paid' => function () {
                return true;
            },
        ];
    }
}
