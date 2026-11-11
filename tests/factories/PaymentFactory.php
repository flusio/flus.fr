<?php

namespace tests\factories;

use Minz\Database;
use Website\models;

/**
 * @extends Database\Factory<models\Payment>
 */
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
            'id' => fn(): string => \Minz\Random::hex(32),

            'created_at' => fn() => $faker->dateTime,

            'is_paid' => fn() => $faker->boolean,

            'type' => fn() => $faker->randomElement(['common_pot', 'subscription']),

            'account_id' => fn() => AccountFactory::create()->id,

            'amount' => fn() => $faker->numberBetween(100, 12000),
        ];
    }
}
