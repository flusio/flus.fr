<?php

namespace Website\controllers\admin;

use Website\models;
use tests\factories\PaymentFactory;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testIndexRendersCorrectly()
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'admin/payments/index.phtml');
    }

    public function testIndexFailsIfNotConnected()
    {
        $response = $this->appRun('GET', '/admin');

        $this->assertResponseCode($response, 302, '/admin/login');
    }

    public function testInitRendersCorrectly()
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin/payments/new');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'admin/payments/init.phtml');
    }

    public function testInitFailsIfNotConnected()
    {
        $response = $this->appRun('GET', '/admin/payments/new');

        $this->assertResponseCode($response, 302, '/admin/login?from=admin%2Fpayments%23init');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateRedirectsCorrectly($type, $email, $amount)
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => \Minz\Csrf::generate(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=payment_created');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateGenerateAnInvoiceNumber($type, $email, $amount)
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => \Minz\Csrf::generate(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=payment_created');
        $payment = models\Payment::take();
        $this->assertNotNull($payment->invoice_number);
        $this->assertNull($payment->completed_at);
        $this->assertFalse($payment->is_paid);
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfTypeIsInvalid($type, $email, $amount)
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => \Minz\Csrf::generate(),
            'type' => 'invalid',
            'email' => $email,
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Le type de paiement est invalide');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfEmailIsInvalid($type, $email, $amount)
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => \Minz\Csrf::generate(),
            'type' => $type,
            'email' => 'not an email',
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'L’adresse courriel que vous avez fournie est invalide.');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfNotConnected($type, $email, $amount)
    {
        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => \Minz\Csrf::generate(),
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 302, '/admin/login?from=admin%2Fpayments%23init');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfCsrfIsInvalid($type, $email, $amount)
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => 'not the token',
            'type' => $type,
            'email' => $email,
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
    }

    public function testShowRendersCorrectly()
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create();

        $response = $this->appRun('GET', '/admin/payments/' . $payment->id);

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'admin/payments/show.phtml');
    }

    public function testShowFailsIfInvalidId()
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin/payments/invalid');

        $this->assertResponseCode($response, 404);
    }

    public function testShowFailsIfNotConnected()
    {
        $payment = PaymentFactory::create();

        $response = $this->appRun('GET', '/admin/payments/' . $payment->id);

        $this->assertResponseCode($response, 302, '/admin/login?from=admin%2Fpayments%23index');
    }

    public function testConfirmRendersCorrectly()
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment->id}/confirm", [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=payment_confirmed');
        $payment = $payment->reload();
        $this->assertTrue($payment->is_paid);
    }

    public function testConfirmFailsIfInvalidId()
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/not-an-id/confirm", [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 404);
    }

    public function testConfirmFailsIfAlreadyPaid()
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => true,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment->id}/confirm", [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Ce paiement a déjà été payé');
    }

    public function testConfirmFailsIfNotConnected()
    {
        $payment = PaymentFactory::create([
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment->id}/confirm", [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin/login?from=admin%2Fpayments%23index');
        $payment = $payment->reload();
        $this->assertFalse($payment->is_paid);
    }

    public function testConfirmFailsIfCsrfIsInvalid()
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment->id}/confirm", [
            'csrf' => 'not the token',
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
        $payment = $payment->reload();
        $this->assertFalse($payment->is_paid);
    }

    public function testDestroyRendersCorrectly()
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => false,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment->id . '/destroy', [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=payment_deleted');
        $this->assertFalse(models\Payment::exists($payment->id));
    }

    public function testDestroyFailsIfInvalidId()
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/invalid/destroy', [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 404);
    }

    public function testDestroyFailsIfNotConnected()
    {
        $payment = PaymentFactory::create([
            'is_paid' => false,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment->id . '/destroy', [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin/login?from=admin%2Fpayments%23index');
        $this->assertTrue(models\Payment::exists($payment->id));
    }

    public function testDestroyFailsIfCsrfIsInvalid()
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => false,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment->id . '/destroy', [
            'csrf' => 'not the token',
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
        $this->assertTrue(models\Payment::exists($payment->id));
    }

    public function testDestroyFailsIfIsPaidIsTrue()
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => true,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment->id . '/destroy', [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Ce paiement a déjà été payé');
        $this->assertTrue(models\Payment::exists($payment->id));
    }

    public function testDestroyFailsIfInvoiceNumberIsNotNull()
    {
        $this->loginAdmin();
        $invoice_number = $this->fake('dateTime')->format('Y-m') . sprintf('-%04d', $this->fake('randomNumber', 4));
        $payment = PaymentFactory::create([
            'is_paid' => false,
            'invoice_number' => $invoice_number,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment->id . '/destroy', [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Ce paiement est associé à une facture');
        $this->assertTrue(models\Payment::exists($payment->id));
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
            ];
        }

        return $datasets;
    }
}
