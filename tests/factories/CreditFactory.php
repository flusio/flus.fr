<?php

namespace tests\factories;

use Minz\Database;
use Website\models;

/**
 * @extends Database\Factory<models\Payment>
 */
class CreditFactory extends Database\Factory
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

            'type' => function (): string {
                return 'credit';
            },

            'account_id' => function () {
                return AccountFactory::create()->id;
            },

            'amount' => function () use ($faker) {
                return $faker->numberBetween(100, 12000);
            },

            'credited_payment_id' => function () {
                return PaymentFactory::create()->id;
            },
        ];
    }
}
