<?php

namespace Website\controllers;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;
use Website\models;

class SubscriptionsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\TimeHelper;

    /**
     * @dataProvider initParamsProvider
     */
    public function testInitRendersCorrectly($address)
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'subscriptions/init.phtml');
    }

    /**
     * @dataProvider initParamsProvider
     */
    public function testInitShowsInfoIfNotExpired($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('randomDigitNotNull'), 'days');
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre abonnement expirera le');
    }

    /**
     * @dataProvider initParamsProvider
     */
    public function testInitRendersIfOngoingPayment($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('randomDigitNotNull'), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'account_id' => $user['account_id'],
            'completed_at' => null,
        ]);

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Attention, vous avez un paiement en cours de traitement');
    }

    public function testInitRedirectsIfNoAddress()
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponseCode($response, 302, '/account/address');
    }

    /**
     * @dataProvider initParamsProvider
     */
    public function testInitFailsIfNotConnected($address)
    {
        AccountFactory::create([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponseCode($response, 401);
    }

    /**
     * @dataProvider renewParamsProvider
     */
    public function testRenewCreatesAPaymentAndRedirects($address, $frequency)
    {
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $account = models\Account::find($user['account_id']);

        $this->assertSame(0, models\Payment::count());

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);

        $this->assertSame(1, models\Payment::count());

        $payment = models\Payment::take();
        $this->assertResponseCode($response, 302, "/payments/{$payment->id}/pay");
        $expected_amount = $frequency === 'month' ? 300 : 3000;
        $this->assertNull($payment->completed_at);
        $this->assertSame($expected_amount, $payment->amount);
        $this->assertSame($frequency, $payment->frequency);
        $this->assertSame($account->id, $payment->account_id);
        $this->assertNotNull($payment->payment_intent_id);
        $this->assertNotNull($payment->session_id);
    }

    /**
     * @dataProvider renewParamsProvider
     */
    public function testRenewSavesPreferredFrequency($address, $frequency)
    {
        $frequency = 'year';
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'preferred_frequency' => 'month',
        ]);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'account_id' => $user['account_id'],
            'frequency' => $frequency,
        ]);

        $account = models\Account::find($user['account_id']);
        $this->assertSame('year', $account->preferred_frequency);
    }

    /**
     * @dataProvider renewParamsProvider
     */
    public function testRenewRedirectsIfNoAddress($address, $frequency)
    {
        $user = $this->loginUser();
        $account = models\Account::find($user['account_id']);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);

        $this->assertResponseCode($response, 302, '/account/address');
        $this->assertSame(0, models\Payment::count());
    }

    /**
     * @dataProvider renewParamsProvider
     */
    public function testRenewFailsIfNotConnected($address, $frequency)
    {
        $account = AccountFactory::create([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);

        $this->assertResponseCode($response, 401);
        $this->assertSame(0, models\Payment::count());
    }

    /**
     * @dataProvider renewParamsProvider
     */
    public function testRenewFailsIfFrequencyIsInvalid($address, $frequency)
    {
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $account = models\Account::find($user['account_id']);
        $frequency = $this->fake('word');

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Vous devez choisir l’une des deux périodes proposées');
        $this->assertSame(0, models\Payment::count());
    }

    /**
     * @dataProvider renewParamsProvider
     */
    public function testRenewFailsIfCsrfIsInvalid($address, $frequency)
    {
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $account = models\Account::find($user['account_id']);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => 'not the token',
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
        $this->assertSame(0, models\Payment::count());
    }

    public function initParamsProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
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

    public function renewParamsProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'address1' => $faker->streetAddress,
                    'postcode' => $faker->postcode,
                    'city' => $faker->city,
                ],
                $faker->randomElement(['month', 'year']),
            ];
        }

        return $datasets;
    }
}
