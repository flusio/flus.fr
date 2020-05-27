<?php

namespace Website\admin;

use Website\models;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testIndexRendersCorrectly()
    {
        $faker = \Faker\Factory::create();
        $created_at = $faker->dateTime;
        $this->freeze($created_at);

        $this->create('payments', [
            'created_at' => $created_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);

        $this->login();

        $request = new \Minz\Request('GET', '/admin');

        $response = self::$application->run($request);

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'admin/payments/index.phtml');
    }

    public function testIndexFailsIfNotConnected()
    {
        $request = new \Minz\Request('GET', '/admin');

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin/login');
    }

    public function testInitRendersCorrectly()
    {
        $this->login();

        $request = new \Minz\Request('GET', '/admin/payments/new');

        $response = self::$application->run($request);

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'admin/payments/init.phtml');
    }

    public function testInitFailsIfNotConnected()
    {
        $request = new \Minz\Request('GET', '/admin/payments/new');

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin/login?from=admin%2Fpayments%23init');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateRedirectsCorrectly($type, $email, $amount, $address)
    {
        $this->login();

        $request = new \Minz\Request('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin?status=payment_created');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateTakesAUsername($type, $email, $amount, $address)
    {
        $this->login();
        $payment_dao = new models\dao\Payment();
        $faker = \Faker\Factory::create();
        $username = $faker->username;

        $request = new \Minz\Request('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'username' => $username,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin?status=payment_created');
        $payment = new models\Payment($payment_dao->take());
        $this->assertSame($username, $payment->username);
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateTakesACompanyVatNumber($type, $email, $amount, $address)
    {
        $this->login();
        $payment_dao = new models\dao\Payment();
        $faker = \Faker\Factory::create('fr_FR');
        $vat = $faker->vat;

        $request = new \Minz\Request('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'company_vat_number' => $vat,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin?status=payment_created');
        $payment = new models\Payment($payment_dao->take());
        $this->assertSame($vat, $payment->company_vat_number);
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateCanGenerateAnInvoiceNumber($type, $email, $amount, $address)
    {
        $this->login();
        $payment_dao = new models\dao\Payment();
        $faker = \Faker\Factory::create();

        $request = new \Minz\Request('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'generate_invoice' => true,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin?status=payment_created');
        $payment = new models\Payment($payment_dao->take());
        $this->assertNotNull($payment->invoice_number);
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfTypeIsInvalid($type, $email, $amount, $address)
    {
        $this->login();

        $request = new \Minz\Request('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => 'invalid',
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 400, 'Le type de paiement est invalide');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfEmailIsInvalid($type, $email, $amount, $address)
    {
        $this->login();

        $request = new \Minz\Request('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => 'not an email',
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 400, 'L’adresse courriel que vous avez fourni est invalide.');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfNotConnected($type, $email, $amount, $address)
    {
        $request = new \Minz\Request('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin/login?from=admin%2Fpayments%23init');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfCsrfIsInvalid($type, $email, $amount, $address)
    {
        $this->login();

        $request = new \Minz\Request('POST', '/admin/payments/new', [
            'csrf' => 'not the token',
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 400, 'Une vérification de sécurité a échoué');
    }

    public function testShowRendersCorrectly()
    {
        $this->login();
        $payment_id = $this->create('payments');

        $request = new \Minz\Request('GET', '/admin/payments/' . $payment_id);

        $response = self::$application->run($request);

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'admin/payments/show.phtml');
    }

    public function testShowFailsIfInvalidId()
    {
        $this->login();

        $request = new \Minz\Request('GET', '/admin/payments/invalid');

        $response = self::$application->run($request);

        $this->assertResponse($response, 404);
    }

    public function testShowFailsIfNotConnected()
    {
        $payment_id = $this->create('payments');

        $request = new \Minz\Request('GET', '/admin/payments/' . $payment_id);

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin/login?from=admin%2Fpayments%23index');
    }

    public function testCompleteRendersCorrectly()
    {
        $payment_dao = new models\dao\Payment();
        $faker = \Faker\Factory::create();
        $this->login();
        $payment_id = $this->create('payments', [
            'completed_at' => null,
        ]);
        $completed_at = $faker->date;

        $request = new \Minz\Request('POST', '/admin/payments/' . $payment_id . '/complete', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'completed_at' => $completed_at,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin?status=payment_completed');
        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertSame($completed_at, $payment->completed_at->format('Y-m-d'));
    }

    public function testCompleteFailsIfInvalidId()
    {
        $payment_dao = new models\dao\Payment();
        $faker = \Faker\Factory::create();
        $this->login();

        $request = new \Minz\Request('POST', '/admin/payments/invalid/complete', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'completed_at' => $faker->date,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 404);
    }

    public function testCompleteFailsIfAlreadyCompleted()
    {
        $payment_dao = new models\dao\Payment();
        $faker = \Faker\Factory::create();
        $this->login();
        $initial_completed_at = $faker->dateTime;
        $payment_id = $this->create('payments', [
            'completed_at' => $initial_completed_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);

        $request = new \Minz\Request('POST', '/admin/payments/' . $payment_id . '/complete', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'completed_at' => $faker->dateTime,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 400, 'Ce paiement a déjà été confirmé');
        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertEquals($initial_completed_at, $payment->completed_at);
    }

    public function testCompleteFailsIfNotConnected()
    {
        $payment_dao = new models\dao\Payment();
        $faker = \Faker\Factory::create();
        $payment_id = $this->create('payments', [
            'completed_at' => null,
        ]);

        $request = new \Minz\Request('POST', '/admin/payments/' . $payment_id . '/complete', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'completed_at' => $faker->date,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin/login?from=admin%2Fpayments%23index');
        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertNull($payment->completed_at);
    }

    public function testCompleteFailsIfCsrfIsInvalid()
    {
        $payment_dao = new models\dao\Payment();
        $faker = \Faker\Factory::create();
        $this->login();
        $payment_id = $this->create('payments', [
            'completed_at' => null,
        ]);

        $request = new \Minz\Request('POST', '/admin/payments/' . $payment_id . '/complete', [
            'csrf' => 'not the token',
            'completed_at' => $faker->date,
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 400, 'Une vérification de sécurité a échoué');
        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertNull($payment->completed_at);
    }

    public function testDestroyRendersCorrectly()
    {
        $payment_dao = new models\dao\Payment();
        $this->login();
        $payment_id = $this->create('payments', [
            'completed_at' => null,
            'invoice_number' => null,
        ]);

        $request = new \Minz\Request('POST', '/admin/payments/' . $payment_id . '/destroy', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin?status=payment_deleted');
        $db_payment = $payment_dao->find($payment_id);
        $this->assertNull($db_payment);
    }

    public function testDestroyFailsIfInvalidId()
    {
        $this->login();

        $request = new \Minz\Request('POST', '/admin/payments/invalid/destroy', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 404);
    }

    public function testDestroyFailsIfNotConnected()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payments', [
            'completed_at' => null,
            'invoice_number' => null,
        ]);

        $request = new \Minz\Request('POST', '/admin/payments/' . $payment_id . '/destroy', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 302, '/admin/login?from=admin%2Fpayments%23index');
        $db_payment = $payment_dao->find($payment_id);
        $this->assertNotNull($db_payment);
    }

    public function testDestroyFailsIfCsrfIsInvalid()
    {
        $payment_dao = new models\dao\Payment();
        $this->login();
        $payment_id = $this->create('payments', [
            'completed_at' => null,
            'invoice_number' => null,
        ]);

        $request = new \Minz\Request('POST', '/admin/payments/' . $payment_id . '/destroy', [
            'csrf' => 'not the token',
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 400, 'Une vérification de sécurité a échoué');
        $db_payment = $payment_dao->find($payment_id);
        $this->assertNotNull($db_payment);
    }

    public function testDestroyFailsIfCompletedAtIsNotNull()
    {
        $faker = \Faker\Factory::create();
        $payment_dao = new models\dao\Payment();
        $this->login();
        $payment_id = $this->create('payments', [
            'completed_at' => $faker->dateTime->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => null,
        ]);

        $request = new \Minz\Request('POST', '/admin/payments/' . $payment_id . '/destroy', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 400, 'Ce paiement a déjà été confirmé');
        $db_payment = $payment_dao->find($payment_id);
        $this->assertNotNull($db_payment);
    }

    public function testDestroyFailsIfInvoiceNumberIsNotNull()
    {
        $faker = \Faker\Factory::create();
        $payment_dao = new models\dao\Payment();
        $this->login();
        $invoice_number = $faker->dateTime->format('Y-m') . sprintf('-%04d', $faker->randomNumber(4));
        $payment_id = $this->create('payments', [
            'completed_at' => null,
            'invoice_number' => $invoice_number,
        ]);

        $request = new \Minz\Request('POST', '/admin/payments/' . $payment_id . '/destroy', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 400, 'Ce paiement est associé à une facture');
        $db_payment = $payment_dao->find($payment_id);
        $this->assertNotNull($db_payment);
    }

    public function createProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                $faker->randomElement(['common_pot', 'subscription_month', 'subscription_year']),
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
