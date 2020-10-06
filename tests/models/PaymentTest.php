<?php

namespace Website\models;

class PaymentTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;

    /**
     * @dataProvider propertiesProvider
     */
    public function testComplete($type, $email, $amount, $address)
    {
        $completed_at = $this->fake('dateTime');
        $payment = Payment::init($type, $email, $amount, $address);

        $payment->complete($completed_at);

        $this->assertEquals($completed_at, $payment->completed_at);
    }

    /**
     * @dataProvider propertiesProvider
     */
    public function testCompleteSetsInvoiceNumber($type, $email, $amount, $address)
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $payment = Payment::init($type, $email, $amount, $address);

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0001';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    /**
     * @dataProvider propertiesProvider
     */
    public function testCompleteIncrementsInvoiceNumberOverMonths($type, $email, $amount, $address)
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);

        $this->create('payment', [
            'invoice_number' => $now->format('Y') . '-01-0001',
        ]);
        $this->create('payment', [
            'invoice_number' => $now->format('Y') . '-01-0002',
        ]);

        $payment = Payment::init($type, $email, $amount, $address);

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0003';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    /**
     * @dataProvider propertiesProvider
     */
    public function testCompleteResetsInvoiceNumberOverYears($type, $email, $amount, $address)
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);

        $previous_year = \Minz\Time::ago(1, 'year');
        $this->create('payment', [
            'invoice_number' => $previous_year->format('Y-m') . '-0001',
        ]);
        $this->create('payment', [
            'invoice_number' => $previous_year->format('Y-m') . '-0002',
        ]);

        $payment = Payment::init($type, $email, $amount, $address);

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0001';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    /**
     * @dataProvider propertiesProvider
     */
    public function testCompleteIgnoresNullInvoiceNumbers($type, $email, $amount, $address)
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);

        $this->create('payment', [
            'invoice_number' => null,
        ]);
        $this->create('payment', [
            'invoice_number' => $now->format('Y') . '-01-0001',
        ]);

        $payment = Payment::init($type, $email, $amount, $address);

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0002';
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
                    'country' => $faker->randomElement(\Website\utils\Countries::codes()),
                ],
            ];
        }

        return $datasets;
    }
}
