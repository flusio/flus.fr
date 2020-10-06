<?php

namespace Website\api;

use Website\models;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;

    /**
     * @dataProvider paySubscriptionParamsProvider
     */
    public function testPaySubscriptionRendersCorrectly($email, $username, $frequency, $address)
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);

        $response = $this->appRun('POST', '/api/payments/subscriptions', [
            'email' => $email,
            'username' => $username,
            'frequency' => $frequency,
            'address' => $address,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 200, null, [
            'Content-Type' => 'application/json'
        ]);

        $payment = json_decode($response->render(), true);
        $expected_amount = $frequency === 'month' ? 300 : 3000;
        $this->assertNotNull($payment['id']);
        $this->assertSame($now->getTimestamp(), $payment['created_at']);
        $this->assertNull($payment['completed_at']);
        $this->assertSame($expected_amount, $payment['amount']);
        $this->assertSame($frequency, $payment['frequency']);
    }

    /**
     * @dataProvider paySubscriptionParamsProvider
     */
    public function testPaySubscriptionCreatesAPayment($email, $username, $frequency, $address)
    {
        $payment_dao = new models\dao\Payment();

        $this->assertSame(0, $payment_dao->count());

        $response = $this->appRun('POST', '/api/payments/subscriptions', [
            'email' => $email,
            'username' => $username,
            'frequency' => $frequency,
            'address' => $address,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertSame(1, $payment_dao->count());

        $payment = new models\Payment($payment_dao->take());
        $payment_address = $payment->address();
        $expected_amount = $frequency === 'month' ? 300 : 3000;
        $this->assertSame('subscription', $payment->type);
        $this->assertSame($email, $payment->email);
        $this->assertSame($expected_amount, $payment->amount);
        $this->assertNull($payment->completed_at);
        $this->assertNotNull($payment->payment_intent_id);
        $this->assertSame($address['first_name'], $payment_address['first_name']);
        $this->assertSame($address['last_name'], $payment_address['last_name']);
        $this->assertSame($address['address1'], $payment_address['address1']);
        $this->assertSame($address['postcode'], $payment_address['postcode']);
        $this->assertSame($address['city'], $payment_address['city']);
        $this->assertSame('FR', $payment_address['country']);
        $this->assertSame($username, $payment->username);
        $this->assertSame($frequency, $payment->frequency);
    }

    /**
     * @dataProvider paySubscriptionParamsProvider
     */
    public function testPaySubscriptionAcceptsCountry($email, $username, $frequency, $address)
    {
        $payment_dao = new models\dao\Payment();
        $address['country'] = $this->fake('randomElement', \Website\utils\Countries::codes());

        $response = $this->appRun('POST', '/api/payments/subscriptions', [
            'email' => $email,
            'username' => $username,
            'frequency' => $frequency,
            'address' => $address,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $payment = new models\Payment($payment_dao->take());
        $payment_address = $payment->address();
        $this->assertSame($address['country'], $payment_address['country']);
    }

    /**
     * @dataProvider paySubscriptionParamsProvider
     */
    public function testPaySubscriptionWithMissingAuthenticationReturnsUnauthorized(
        $email,
        $username,
        $frequency,
        $address
    ) {
        $response = $this->appRun('POST', '/api/payments/subscriptions', [
            'email' => $email,
            'username' => $username,
            'frequency' => $frequency,
            'address' => $address,
        ]);

        $this->assertResponse($response, 401);
    }

    /**
     * @dataProvider paySubscriptionParamsProvider
     */
    public function testPaySubscriptionWithWrongFrequencyReturnsBadRequest($email, $username, $frequency, $address)
    {
        $frequency = $this->fake('word');

        $response = $this->appRun('POST', '/api/payments/subscriptions', [
            'email' => $email,
            'username' => $username,
            'frequency' => $frequency,
            'address' => $address,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 400, '`frequency` property is invalid');
    }

    public function testShowRendersCorrectly()
    {
        $created_at = $this->fake('dateTime');
        $completed_at = $this->fake('dateTime');
        $amount = $this->fake('numberBetween', 100, 100000);
        $frequency = $this->fake('randomElement', ['month', 'year']);
        $payment_id = $this->create('payment', [
            'created_at' => $created_at->format(\Minz\Model::DATETIME_FORMAT),
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'amount' => $amount,
            'frequency' => $frequency,
        ]);

        $response = $this->appRun('GET', "/api/payments/{$payment_id}", [], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 200, null, [
            'Content-Type' => 'application/json'
        ]);
        $payment = json_decode($response->render(), true);
        $this->assertSame($payment_id, $payment['id']);
        $this->assertEquals($created_at->getTimestamp(), $payment['created_at']);
        $this->assertEquals($completed_at->getTimestamp(), $payment['completed_at']);
        $this->assertSame($amount, $payment['amount']);
        $this->assertSame($frequency, $payment['frequency']);
    }

    public function testShowWithUnknownIdReturnsNotFound()
    {
        $response = $this->appRun('GET', '/api/payments/unknown', [], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 404);
    }

    public function testShowWithMissingAuthenticationReturnsUnauthorized()
    {
        $created_at = $this->fake('dateTime');
        $completed_at = $this->fake('dateTime');
        $amount = $this->fake('numberBetween', 100, 100000);
        $frequency = $this->fake('randomElement', ['month', 'year']);
        $payment_id = $this->create('payment', [
            'created_at' => $created_at->getTimestamp(),
            'completed_at' => $completed_at->getTimestamp(),
            'amount' => $amount,
            'frequency' => $frequency,
        ]);

        $response = $this->appRun('GET', "/api/payments/{$payment_id}");

        $this->assertResponse($response, 401);
    }

    public function paySubscriptionParamsProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                $faker->email,
                $faker->username,
                $faker->randomElement(['month', 'year']),
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
