<?php

namespace tests\factories;

use Minz\Database;
use Website\models;

/**
 * @extends Database\Factory<models\PotUsage>
 */
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
            'id' => fn(): string => \Minz\Random::hex(32),

            'created_at' => fn() => $faker->dateTime,

            'account_id' => fn() => AccountFactory::create()->id,

            'amount' => fn() => $faker->numberBetween(100, 12000),

            'frequency' => fn() => $faker->randomElement(['month', 'year']),

            // a common pot payment is always completed
            'completed_at' => fn() => $faker->dateTime,

            'is_paid' => fn(): true => true,
        ];
    }
}
