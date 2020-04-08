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
    public function testPayActionRendersCorrectly($email, $amount)
    {
        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 200);
        $pointer = $response->output()->pointer();
        $this->assertSame('stripe/redirection.phtml', $pointer);
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionAcceptsFloatAmounts($email, $amount)
    {
        $faker = \Faker\Factory::create();
        $amount = $faker->randomFloat(2, 1.00, 1000.0);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 200);
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionConfiguresStripe($email, $amount)
    {
        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
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
    public function testPayActionCreatesAPayment($email, $amount)
    {
        $payment_dao = new models\dao\Payment();

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
        ]);

        $this->assertSame(0, $payment_dao->count());
        $response = self::$application->run($request);
        $this->assertSame(1, $payment_dao->count());

        $payment = new models\Payment($payment_dao->take());
        $this->assertSame($email, $payment->email);
        $this->assertSame($amount * 100, $payment->amount);
        $this->assertFalse($payment->completed);
        $this->assertNotNull($payment->payment_intent_id);
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithWrongEmailReturnsABadRequest($email, $amount)
    {
        $faker = \Faker\Factory::create();
        $email = $faker->domainName;

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
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
    public function testPayActionWithAmountLessThan1ReturnsABadRequest($email, $amount)
    {
        $faker = \Faker\Factory::create();
        $amount = $faker->randomFloat(2, 0.0, 0.99);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
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
    public function testPayActionWithAmountMoreThan1000ReturnsABadRequest($email, $amount)
    {
        $faker = \Faker\Factory::create();
        $amount = $faker->numberBetween(1001, PHP_INT_MAX / 100);

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
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
    public function testPayActionWithAmountAsStringReturnsABadRequest($email, $amount)
    {
        $faker = \Faker\Factory::create();
        $amount = $faker->word;

        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être une valeur numérique comprise entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider payParamsProvider
     */
    public function testPayActionWithMissingAmountReturnsABadRequest($email, $amount)
    {
        $request = new \Minz\Request('POST', '/cagnotte', [
            'email' => $email,
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
    public function testPayActionWithMissingEmailReturnsABadRequest($email, $amount)
    {
        $request = new \Minz\Request('POST', '/cagnotte', [
            'amount' => $amount,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse(
            $response,
            400,
            'L’adresse courriel est obligatoire.',
        );
    }

    public function payParamsProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                $faker->email,
                $faker->numberBetween(1, 1000),
            ];
        }

        return $datasets;
    }
}
