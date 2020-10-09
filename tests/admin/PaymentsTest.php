<?php

namespace Website\admin;

use Website\models;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testIndexRendersCorrectly()
    {
        $created_at = $this->fake('dateTime');
        $this->freeze($created_at);

        $this->create('payment', [
            'created_at' => $created_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);

        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin');

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'admin/payments/index.phtml');
    }

    public function testIndexFailsIfNotConnected()
    {
        $response = $this->appRun('GET', '/admin');

        $this->assertResponse($response, 302, '/admin/login');
    }

    public function testInitRendersCorrectly()
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin/payments/new');

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'admin/payments/init.phtml');
    }

    public function testInitFailsIfNotConnected()
    {
        $response = $this->appRun('GET', '/admin/payments/new');

        $this->assertResponse($response, 302, '/admin/login?from=admin%2Fpayments%23init');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateRedirectsCorrectly($type, $email, $amount, $address)
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $this->assertResponse($response, 302, '/admin?status=payment_created');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateTakesAUsername($type, $email, $amount, $address)
    {
        $this->loginAdmin();
        $payment_dao = new models\dao\Payment();
        $username = $this->fake('username');

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'username' => $username,
        ]);

        $this->assertResponse($response, 302, '/admin?status=payment_created');
        $payment = new models\Payment($payment_dao->take());
        $this->assertSame($username, $payment->username);
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateTakesACompanyVatNumber($type, $email, $amount, $address)
    {
        $this->loginAdmin();
        $payment_dao = new models\dao\Payment();
        $faker = \Faker\Factory::create('fr_FR');
        $vat = $faker->vat;

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'company_vat_number' => $vat,
        ]);

        $this->assertResponse($response, 302, '/admin?status=payment_created');
        $payment = new models\Payment($payment_dao->take());
        $this->assertSame($vat, $payment->company_vat_number);
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateCanGenerateAnInvoiceNumber($type, $email, $amount, $address)
    {
        $this->loginAdmin();
        $payment_dao = new models\dao\Payment();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'generate_invoice' => true,
        ]);

        $this->assertResponse($response, 302, '/admin?status=payment_created');
        $payment = new models\Payment($payment_dao->take());
        $this->assertNotNull($payment->invoice_number);
        $this->assertNull($payment->completed_at);
        $this->assertFalse($payment->is_paid);
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfTypeIsInvalid($type, $email, $amount, $address)
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => 'invalid',
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'Le type de paiement est invalide');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfEmailIsInvalid($type, $email, $amount, $address)
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => 'not an email',
            'amount' => $amount,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'L’adresse courriel que vous avez fournie est invalide.');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfNotConnected($type, $email, $amount, $address)
    {
        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $this->assertResponse($response, 302, '/admin/login?from=admin%2Fpayments%23init');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfCsrfIsInvalid($type, $email, $amount, $address)
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => 'not the token',
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'Une vérification de sécurité a échoué');
    }

    public function testShowRendersCorrectly()
    {
        $this->loginAdmin();
        $payment_id = $this->create('payment');

        $response = $this->appRun('GET', '/admin/payments/' . $payment_id);

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'admin/payments/show.phtml');
    }

    public function testShowFailsIfInvalidId()
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin/payments/invalid');

        $this->assertResponse($response, 404);
    }

    public function testShowFailsIfNotConnected()
    {
        $payment_id = $this->create('payment');

        $response = $this->appRun('GET', '/admin/payments/' . $payment_id);

        $this->assertResponse($response, 302, '/admin/login?from=admin%2Fpayments%23index');
    }

    public function testConfirmRendersCorrectly()
    {
        $payment_dao = new models\dao\Payment();
        $this->loginAdmin();
        $payment_id = $this->create('payment', [
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment_id}/confirm", [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $this->assertResponse($response, 302, '/admin?status=payment_confirmed');
        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertTrue($payment->is_paid);
    }

    public function testConfirmFailsIfInvalidId()
    {
        $this->loginAdmin();
        $payment_id = $this->create('payment', [
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/not-an-id/confirm", [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $this->assertResponse($response, 404);
    }

    public function testConfirmFailsIfAlreadyPaid()
    {
        $this->loginAdmin();
        $payment_id = $this->create('payment', [
            'is_paid' => true,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment_id}/confirm", [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $this->assertResponse($response, 400, 'Ce paiement a déjà été payé');
    }

    public function testConfirmFailsIfNotConnected()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment_id}/confirm", [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $this->assertResponse($response, 302, '/admin/login?from=admin%2Fpayments%23index');
        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertFalse($payment->is_paid);
    }

    public function testConfirmFailsIfCsrfIsInvalid()
    {
        $payment_dao = new models\dao\Payment();
        $this->loginAdmin();
        $payment_id = $this->create('payment', [
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment_id}/confirm", [
            'csrf' => 'not the token',
        ]);

        $this->assertResponse($response, 400, 'Une vérification de sécurité a échoué');
        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertFalse($payment->is_paid);
    }

    public function testDestroyRendersCorrectly()
    {
        $payment_dao = new models\dao\Payment();
        $this->loginAdmin();
        $payment_id = $this->create('payment', [
            'is_paid' => false,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment_id . '/destroy', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $this->assertResponse($response, 302, '/admin?status=payment_deleted');
        $db_payment = $payment_dao->find($payment_id);
        $this->assertNull($db_payment);
    }

    public function testDestroyFailsIfInvalidId()
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/invalid/destroy', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $this->assertResponse($response, 404);
    }

    public function testDestroyFailsIfNotConnected()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'is_paid' => false,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment_id . '/destroy', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $this->assertResponse($response, 302, '/admin/login?from=admin%2Fpayments%23index');
        $db_payment = $payment_dao->find($payment_id);
        $this->assertNotNull($db_payment);
    }

    public function testDestroyFailsIfCsrfIsInvalid()
    {
        $payment_dao = new models\dao\Payment();
        $this->loginAdmin();
        $payment_id = $this->create('payment', [
            'is_paid' => false,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment_id . '/destroy', [
            'csrf' => 'not the token',
        ]);

        $this->assertResponse($response, 400, 'Une vérification de sécurité a échoué');
        $db_payment = $payment_dao->find($payment_id);
        $this->assertNotNull($db_payment);
    }

    public function testDestroyFailsIfIsPaidIsTrue()
    {
        $payment_dao = new models\dao\Payment();
        $this->loginAdmin();
        $payment_id = $this->create('payment', [
            'is_paid' => true,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment_id . '/destroy', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

        $this->assertResponse($response, 400, 'Ce paiement a déjà été payé');
        $db_payment = $payment_dao->find($payment_id);
        $this->assertNotNull($db_payment);
    }

    public function testDestroyFailsIfInvoiceNumberIsNotNull()
    {
        $payment_dao = new models\dao\Payment();
        $this->loginAdmin();
        $invoice_number = $this->fake('dateTime')->format('Y-m') . sprintf('-%04d', $this->fake('randomNumber', 4));
        $payment_id = $this->create('payment', [
            'is_paid' => false,
            'invoice_number' => $invoice_number,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment_id . '/destroy', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
        ]);

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
