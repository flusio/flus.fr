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

    public function testIndexRendersCorrectly(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin');

        $this->assertResponseCode($response, 200);
        $this->assertResponseTemplateName($response, 'admin/payments/index.phtml');
    }

    public function testIndexFailsIfNotConnected(): void
    {
        $response = $this->appRun('GET', '/admin');

        $this->assertResponseCode($response, 302, '/admin/login');
    }

    public function testShowRendersCorrectly(): void
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create();

        $response = $this->appRun('GET', '/admin/payments/' . $payment->id);

        $this->assertResponseCode($response, 200);
        $this->assertResponseTemplateName($response, 'admin/payments/show.phtml');
    }

    public function testShowFailsIfInvalidId(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin/payments/invalid');

        $this->assertResponseCode($response, 404);
    }

    public function testShowFailsIfNotConnected(): void
    {
        $payment = PaymentFactory::create();

        $response = $this->appRun('GET', '/admin/payments/' . $payment->id);

        $this->assertResponseCode($response, 302, '/admin/login');
    }

    public function testConfirmRendersCorrectly(): void
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment->id}/confirm", [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=payment_confirmed');
        $payment = $payment->reload();
        $this->assertTrue($payment->is_paid);
    }

    public function testConfirmFailsIfInvalidId(): void
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/not-an-id/confirm", [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 404);
    }

    public function testConfirmFailsIfAlreadyPaid(): void
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => true,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment->id}/confirm", [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Ce paiement a déjà été payé');
    }

    public function testConfirmFailsIfNotConnected(): void
    {
        $payment = PaymentFactory::create([
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment->id}/confirm", [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin/login');
        $payment = $payment->reload();
        $this->assertFalse($payment->is_paid);
    }

    public function testConfirmFailsIfCsrfIsInvalid(): void
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

    public function testDestroyRendersCorrectly(): void
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => false,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment->id . '/destroy', [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=payment_deleted');
        $this->assertFalse(models\Payment::exists($payment->id));
    }

    public function testDestroyFailsIfInvalidId(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/invalid/destroy', [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 404);
    }

    public function testDestroyFailsIfNotConnected(): void
    {
        $payment = PaymentFactory::create([
            'is_paid' => false,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment->id . '/destroy', [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin/login');
        $this->assertTrue(models\Payment::exists($payment->id));
    }

    public function testDestroyFailsIfCsrfIsInvalid(): void
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

    public function testDestroyFailsIfIsPaidIsTrue(): void
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => true,
            'invoice_number' => null,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment->id . '/destroy', [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Ce paiement a déjà été payé');
        $this->assertTrue(models\Payment::exists($payment->id));
    }

    public function testDestroyFailsIfInvoiceNumberIsNotNull(): void
    {
        $this->loginAdmin();
        $invoice_number = $this->fake('dateTime')->format('Y-m') . sprintf('-%04d', $this->fake('randomNumber', 4));
        $payment = PaymentFactory::create([
            'is_paid' => false,
            'invoice_number' => $invoice_number,
        ]);

        $response = $this->appRun('POST', '/admin/payments/' . $payment->id . '/destroy', [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Ce paiement est associé à une facture');
        $this->assertTrue(models\Payment::exists($payment->id));
    }
}
