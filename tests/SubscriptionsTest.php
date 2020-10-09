<?php

namespace Website;

class SubscriptionsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\FactoriesHelper;
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

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'subscriptions/init.phtml');
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
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponse($response, 200, 'Votre abonnement expirera le');
    }

    public function testInitRedirectsIfNoAddress()
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponse($response, 302, '/account/address');
    }

    /**
     * @dataProvider initParamsProvider
     */
    public function testInitFailsIfNotConnected($address)
    {
        $this->create('account', [
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponse($response, 401);
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
        $payment_dao = new models\dao\Payment();
        $account_dao = new models\dao\Account();
        $account = new models\Account($account_dao->find($user['account_id']));

        $this->assertSame(0, $payment_dao->count());

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);

        $this->assertSame(1, $payment_dao->count());

        $payment = new models\Payment($payment_dao->listAll()[0]);
        $this->assertResponse($response, 302, "/payments/{$payment->id}/pay");
        $expected_amount = $frequency === 'month' ? 300 : 3000;
        $this->assertNull($payment->completed_at);
        $this->assertSame($expected_amount, $payment->amount);
        $this->assertSame($frequency, $payment->frequency);
        $this->assertSame($account->id, $payment->account_id);
        $this->assertSame($account->email, $payment->email);
        $this->assertSame($account->address_first_name, $payment->address_first_name);
        $this->assertSame($account->address_last_name, $payment->address_last_name);
        $this->assertSame($account->address_address1, $payment->address_address1);
        $this->assertSame($account->address_postcode, $payment->address_postcode);
        $this->assertSame($account->address_city, $payment->address_city);
        $this->assertSame($account->address_country, $payment->address_country);
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
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'account_id' => $user['account_id'],
            'frequency' => $frequency,
        ]);

        $account_dao = new models\dao\Account();
        $account = new models\Account($account_dao->find($user['account_id']));
        $this->assertSame('year', $account->preferred_frequency);
    }

    /**
     * @dataProvider renewParamsProvider
     */
    public function testRenewRedirectsIfNoAddress($address, $frequency)
    {
        $user = $this->loginUser();
        $payment_dao = new models\dao\Payment();
        $account_dao = new models\dao\Account();
        $account = new models\Account($account_dao->find($user['account_id']));

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);

        $this->assertResponse($response, 302, '/account/address');
        $this->assertSame(0, $payment_dao->count());
    }

    /**
     * @dataProvider renewParamsProvider
     */
    public function testRenewFailsIfNotConnected($address, $frequency)
    {
        $account_id = $this->create('account', [
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $payment_dao = new models\dao\Payment();
        $account_dao = new models\dao\Account();
        $account = new models\Account($account_dao->find($account_id));

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);

        $this->assertResponse($response, 401);
        $this->assertSame(0, $payment_dao->count());
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
        $payment_dao = new models\dao\Payment();
        $account_dao = new models\dao\Account();
        $account = new models\Account($account_dao->find($user['account_id']));
        $frequency = $this->fake('word');

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);

        $this->assertResponse($response, 400, 'Vous devez choisir l’une des deux périodes proposées');
        $this->assertSame(0, $payment_dao->count());
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
        $payment_dao = new models\dao\Payment();
        $account_dao = new models\dao\Account();
        $account = new models\Account($account_dao->find($user['account_id']));

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => 'not the token',
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);

        $this->assertResponse($response, 400, 'Une vérification de sécurité a échoué');
        $this->assertSame(0, $payment_dao->count());
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
