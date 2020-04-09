<?php

namespace Website\models;

use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    protected static $schema;

    /**
     * @beforeClass
     */
    public static function loadSchema()
    {
        $app_path = \Minz\Configuration::$app_path;
        $schema_path = $app_path . '/src/schema.sql';
        self::$schema = file_get_contents($schema_path);
    }

    /**
     * @before
     */
    public function initDatabase()
    {
        $database = \Minz\Database::get();
        $database->exec(self::$schema);
    }

    /**
     * @after
     */
    public function dropDatabase()
    {
        \Minz\Database::drop();
    }

    /**
     * @after
     */
    public function unfreezeTime(): void
    {
        \Minz\Time::unfreeze();
    }

    /**
     * @dataProvider propertiesProvider
     */
    public function testCompleteSetsCompletedAtToCurrentDateTime($type, $email, $amount, $address)
    {
        $faker = \Faker\Factory::create();
        $now = $faker->dateTime;
        \Minz\Time::freeze($now);

        $payment = Payment::init($type, $email, $amount, $address);

        $payment->complete();

        $this->assertEquals($now, $payment->completed_at);
    }

    /**
     * @dataProvider propertiesProvider
     */
    public function testCompleteSetsInvoiceNumber($type, $email, $amount, $address)
    {
        $faker = \Faker\Factory::create();
        $now = $faker->dateTime;
        \Minz\Time::freeze($now);

        $payment = Payment::init($type, $email, $amount, $address);

        $payment->complete();

        $expected_invoice_number = $now->format('Y-m') . '-0001';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    /**
     * @dataProvider propertiesProvider
     */
    public function testCompleteIncrementsInvoiceNumberOverMonths($type, $email, $amount, $address)
    {
        $faker = \Faker\Factory::create();
        $now = $faker->dateTime;
        \Minz\Time::freeze($now);

        $payment_factory = new \Minz\Tests\DatabaseFactory('payments');
        $payment_factory->create([
            'invoice_number' => $now->format('Y') . '-01-0001',
        ]);

        $payment = Payment::init($type, $email, $amount, $address);

        $payment->complete();

        $expected_invoice_number = $now->format('Y-m') . '-0002';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    /**
     * @dataProvider propertiesProvider
     */
    public function testCompleteResetsInvoiceNumberOverYears($type, $email, $amount, $address)
    {
        $faker = \Faker\Factory::create();
        $now = $faker->dateTime;
        \Minz\Time::freeze($now);

        $previous_year = \Minz\Time::ago(1, 'year');
        $payment_factory = new \Minz\Tests\DatabaseFactory('payments');
        $payment_factory->create([
            'invoice_number' => $previous_year->format('Y-m') . '-0001',
        ]);

        $payment = Payment::init($type, $email, $amount, $address);

        $payment->complete();

        $expected_invoice_number = $now->format('Y-m') . '-0001';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    public function propertiesProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                $faker->randomElement(['common_pot', 'subscription']),
                $faker->email,
                $faker->numberBetween(1, 1000),
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'address1' => $faker->streetAddress,
                    'postcode' => $faker->postcode,
                    'city' => $faker->city,
                ],
            ];
        }

        return $datasets;
    }
}
