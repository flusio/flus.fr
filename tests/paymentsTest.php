<?php

namespace Website\controllers\home;

use Minz\Tests\IntegrationTestCase;
use Website\models;
use Website\services;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
class paymentsTest extends IntegrationTestCase
{
    public function testInitActionRendersCorrectly()
    {
        $request = new \Minz\Request('GET', '/cagnotte');

        $response = self::$application->run($request);

        $this->assertResponse($response, 200);
        $pointer = $response->output()->pointer();
        $this->assertSame('payments/init.phtml', $pointer);
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionRendersCorrectly($email, $amount, $address)
    {
        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 200);
        $pointer = $response->output()->pointer();
        $this->assertSame('stripe/redirection.phtml', $pointer);
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionAcceptsFloatAmounts($email, $amount, $address)
    {
        $faker = \Faker\Factory::create();
        $amount = $faker->randomFloat(2, 1.00, 1000.0);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 200);
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionConfiguresStripe($email, $amount, $address)
    {
        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $variables = $response->output()->variables();
        $headers = $response->headers(true);
        $csp = $headers['Content-Security-Policy'];

        $this->assertSame(
            \Minz\Configuration::$application['stripe_public_key'],
            $variables['stripe_public_key']
        );
        $this->assertTrue(strlen($variables['stripe_session_id']) > 0);
        $this->assertSame(
            "'self' js.stripe.com",
            $csp['default-src']
        );
        $this->assertSame(
            "'self' 'unsafe-inline' js.stripe.com",
            $csp['script-src']
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionCreatesAPayment($email, $amount, $address)
    {
        $payment_dao = new models\dao\Payment();

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $this->assertSame(0, $payment_dao->count());
        $response = self::$application->run($request);
        $this->assertSame(1, $payment_dao->count());

        $payment = new models\Payment($payment_dao->take());
        $payment_address = $payment->address();
        $this->assertSame('common_pot', $payment->type);
        $this->assertSame($email, $payment->email);
        $this->assertSame($amount * 100, $payment->amount);
        $this->assertNull($payment->completed_at);
        $this->assertNotNull($payment->payment_intent_id);
        $this->assertSame($address['first_name'], $payment_address['first_name']);
        $this->assertSame($address['last_name'], $payment_address['last_name']);
        $this->assertSame($address['address1'], $payment_address['address1']);
        $this->assertSame($address['postcode'], $payment_address['postcode']);
        $this->assertSame($address['city'], $payment_address['city']);
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithWrongEmailReturnsABadRequest($email, $amount, $address)
    {
        $faker = \Faker\Factory::create();
        $email = $faker->domainName;

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'L’adresse courriel que vous avez fourni est invalide.'
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithAmountLessThan1ReturnsABadRequest($email, $amount, $address)
    {
        $faker = \Faker\Factory::create();
        $amount = $faker->randomFloat(2, 0.0, 0.99);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithAmountMoreThan1000ReturnsABadRequest($email, $amount, $address)
    {
        $faker = \Faker\Factory::create();
        $amount = $faker->numberBetween(1001, PHP_INT_MAX / 100);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithAmountAsStringReturnsABadRequest($email, $amount, $address)
    {
        $faker = \Faker\Factory::create();
        $amount = $faker->word;

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithMissingAmountReturnsABadRequest($email, $amount, $address)
    {
        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithMissingEmailReturnsABadRequest($email, $amount, $address)
    {
        $request = new \Minz\Request('POST', '/cagnotte', [
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'L’adresse courriel est obligatoire.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithMissingFirstNameReturnsABadRequest($email, $amount, $address)
    {
        unset($address['first_name']);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Votre prénom est obligatoire.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithMissingLastNameReturnsABadRequest($email, $amount, $address)
    {
        unset($address['last_name']);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Votre nom est obligatoire.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithMissingAddress1ReturnsABadRequest($email, $amount, $address)
    {
        unset($address['address1']);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Votre adresse est obligatoire.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithMissingPostcodeReturnsABadRequest($email, $amount, $address)
    {
        unset($address['postcode']);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Votre code postal est obligatoire.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithMissingCityReturnsABadRequest($email, $amount, $address)
    {
        unset($address['city']);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Votre ville est obligatoire.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithAddressAsSingleParamReturnsABadRequest($email, $amount, $address)
    {
        $faker = \Faker\Factory::create();
        $address = $faker->address;

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Votre prénom est obligatoire.',
        );
    }

    /**
     * @dataProvider paySubscriptionParamsProvider
     */
    public function testPaySubscriptionRendersCorrectly($email, $username, $frequency, $address)
    {
        $request = new \Minz\Request('POST', '/payments/subscriptions', [
            'email' => $email,
            'username' => $username,
            'frequency' => $frequency,
            'address' => $address,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 200);
        $pointer = $response->output()->pointer();
        $this->assertSame('stripe/redirection.phtml', $pointer);
    }

    /**
     * @dataProvider paySubscriptionParamsProvider
     */
    public function testPaySubscriptionCreatesAPayment($email, $username, $frequency, $address)
    {
        $payment_dao = new models\dao\Payment();

        $request = new \Minz\Request('POST', '/payments/subscriptions', [
            'email' => $email,
            'username' => $username,
            'frequency' => $frequency,
            'address' => $address,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertSame(0, $payment_dao->count());
        $response = self::$application->run($request);
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
        $this->assertSame($username, $payment->username);
        $this->assertSame($frequency, $payment->frequency);
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
        $request = new \Minz\Request('POST', '/payments/subscriptions', [
            'email' => $email,
            'username' => $username,
            'frequency' => $frequency,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 401);
    }

    /**
     * @dataProvider paySubscriptionParamsProvider
     */
    public function testPaySubscriptionWithWrongFrequencyReturnsBadRequest($email, $username, $frequency, $address)
    {
        $faker = \Faker\Factory::create();
        $frequency = $faker->word;

        $request = new \Minz\Request('POST', '/payments/subscriptions', [
            'email' => $email,
            'username' => $username,
            'frequency' => $frequency,
            'address' => $address,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 400);
    }

    public function payParamsProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
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
