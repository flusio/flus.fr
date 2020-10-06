<?php

namespace Website;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testInitActionRendersCorrectly()
    {
        $response = $this->appRun('GET', '/cagnotte');

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'payments/init.phtml');
    }

    public function testInitActionShowsAmountOfTheCommonPot()
    {
        $amount_common_pot = $this->fake('numberBetween', 100, 100000);
        $amount_subscriptions = $this->fake('numberBetween', 100, 100000);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => $amount_common_pot,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $this->create('payment', [
            'type' => 'subscription',
            'amount' => $amount_subscriptions,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);

        $response = $this->appRun('GET', '/cagnotte');

        $expected_amount = number_format(($amount_common_pot / 100), 2, ',', '&nbsp') . '&nbsp;€';
        $this->assertResponse($response, 200, $expected_amount);
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotRedirectsCorrectly($email, $amount, $address)
    {
        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $payment_dao = new models\dao\Payment();
        $payment_id = $payment_dao->take()['id'];
        $redirect_to = "/payments/{$payment_id}/pay";
        $this->assertResponse($response, 302, $redirect_to);
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotAcceptsFloatAmounts($email, $amount, $address)
    {
        $amount = $this->fake('randomFloat', 2, 1.00, 1000.0);

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 302);
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotCreatesAPayment($email, $amount, $address)
    {
        $payment_dao = new models\dao\Payment();

        $this->assertSame(0, $payment_dao->count());

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

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
        $this->assertSame('FR', $payment_address['country']);
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotAcceptsCountry($email, $amount, $address)
    {
        $payment_dao = new models\dao\Payment();
        $address['country'] = $this->fake('randomElement', \Website\utils\Countries::codes());

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $payment = new models\Payment($payment_dao->take());
        $payment_address = $payment->address();
        $this->assertSame($address['country'], $payment_address['country']);
    }
    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithoutAcceptingCgvReturnsABadRequest($email, $amount, $address)
    {
        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => false,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Vous devez accepter ces conditions pour participer à la cagnotte.'
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithWrongEmailReturnsABadRequest($email, $amount, $address)
    {
        $email = $this->fake('domainName');

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'L’adresse courriel que vous avez fourni est invalide.'
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithAmountLessThan1ReturnsABadRequest($email, $amount, $address)
    {
        $amount = $this->fake('randomFloat', 2, 0.0, 0.99);

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithAmountMoreThan1000ReturnsABadRequest($email, $amount, $address)
    {
        $amount = $this->fake('numberBetween', 1001, PHP_INT_MAX / 100);

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithAmountAsStringReturnsABadRequest($email, $amount, $address)
    {
        $amount = $this->fake('word');

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithMissingAmountReturnsABadRequest($email, $amount, $address)
    {
        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithMissingEmailReturnsABadRequest($email, $amount, $address)
    {
        $response = $this->appRun('POST', '/cagnotte', [
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'L’adresse courriel est obligatoire.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithMissingFirstNameReturnsABadRequest($email, $amount, $address)
    {
        unset($address['first_name']);

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Votre prénom est obligatoire.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithMissingLastNameReturnsABadRequest($email, $amount, $address)
    {
        unset($address['last_name']);

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Votre nom est obligatoire.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithMissingAddress1ReturnsABadRequest($email, $amount, $address)
    {
        unset($address['address1']);

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Votre adresse est obligatoire.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithMissingPostcodeReturnsABadRequest($email, $amount, $address)
    {
        unset($address['postcode']);

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Votre code postal est obligatoire.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithMissingCityReturnsABadRequest($email, $amount, $address)
    {
        unset($address['city']);

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Votre ville est obligatoire.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithInvalidCountryReturnsABadRequest($email, $amount, $address)
    {
        $address['country'] = 'invalid';

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Le pays que vous avez renseigné est invalide.',
        );
    }

    /**
     * @dataProvider payCommonPotProvider
     */
    public function testPayCommonPotWithAddressAsSingleParamReturnsABadRequest($email, $amount, $address)
    {
        $address = $this->fake('address');

        $response = $this->appRun('POST', '/cagnotte', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'accept_cgv' => true,
        ]);

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
        $now = $this->fake('dateTime');
        $this->freeze($now);

        $response = $this->appRun('POST', '/payments/subscriptions', [
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

        $response = $this->appRun('POST', '/payments/subscriptions', [
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

        $response = $this->appRun('POST', '/payments/subscriptions', [
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
        $response = $this->appRun('POST', '/payments/subscriptions', [
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

        $response = $this->appRun('POST', '/payments/subscriptions', [
            'email' => $email,
            'username' => $username,
            'frequency' => $frequency,
            'address' => $address,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 400);
    }

    public function testPayRendersCorrectly()
    {
        $payment_id = $this->create('payment');

        $response = $this->appRun('GET', "/payments/{$payment_id}/pay");

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'stripe/redirection.phtml');
    }

    public function testPayConfiguresStripe()
    {
        $session_id = $this->fake('regexify', 'cs_test_[\w\d]{56}');
        $payment_id = $this->create('payment', [
            'session_id' => $session_id,
        ]);

        $response = $this->appRun('GET', "/payments/{$payment_id}/pay");

        $variables = $response->output()->variables();
        $headers = $response->headers(true);
        $csp = $headers['Content-Security-Policy'];

        $this->assertSame(
            \Minz\Configuration::$application['stripe_public_key'],
            $variables['stripe_public_key']
        );
        $this->assertSame(
            $session_id,
            $variables['stripe_session_id']
        );
        $this->assertSame(
            "'self' js.stripe.com",
            $csp['default-src']
        );
        $this->assertSame(
            "'self' 'unsafe-inline' js.stripe.com",
            $csp['script-src']
        );
    }

    public function testPayWithUnknownIdReturnsANotFound()
    {
        $response = $this->appRun('GET', "/payments/unknown/pay");

        $this->assertResponse($response, 404);
    }

    public function testPayWithPaidPaymentReturnsBadRequest()
    {
        $payment_id = $this->create('payment', [
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);

        $response = $this->appRun('GET', "/payments/{$payment_id}/pay");

        $this->assertResponse($response, 400);
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

        $response = $this->appRun('GET', "/payments/{$payment_id}", [], [
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
        $response = $this->appRun('GET', '/payments/unknown', [], [
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

        $response = $this->appRun('GET', "/payments/{$payment_id}");

        $this->assertResponse($response, 401);
    }

    public function testSucceededRendersCorrectly()
    {
        $response = $this->appRun('GET', '/merci');

        $this->assertResponse($response, 200, 'Votre paiement a bien été pris en compte');
    }

    public function testCanceledRendersCorrectly()
    {
        $response = $this->appRun('GET', '/annulation');

        $this->assertResponse($response, 200, 'Votre paiement a bien été annulé');
    }

    public function payCommonPotProvider()
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
