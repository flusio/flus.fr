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
        $this->assertResponsePointer($response, 'admin/payments/index.phtml');
    }

    public function testIndexFailsIfNotConnected(): void
    {
        $response = $this->appRun('GET', '/admin');

        $this->assertResponseCode($response, 302, '/admin/login');
    }

    public function testInitRendersCorrectly(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin/payments/new');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'admin/payments/init.phtml');
    }

    public function testInitFailsIfNotConnected(): void
    {
        $response = $this->appRun('GET', '/admin/payments/new');

        $this->assertResponseCode($response, 302, '/admin/login?from=admin%2Fpayments%23init');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateRedirectsCorrectly(string $email, int $amount): void
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=payment_created');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateGenerateAnInvoiceNumber(string $email, int $amount): void
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=payment_created');
        $payment = models\Payment::take();
        $this->assertNotNull($payment);
        $this->assertNotNull($payment->invoice_number);
        $this->assertNull($payment->completed_at);
        $this->assertFalse($payment->is_paid);
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfEmailIsInvalid(string $email, int $amount): void
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => 'not an email',
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'L’adresse courriel que vous avez fournie est invalide.');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfNotConnected(string $email, int $amount): void
    {
        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 302, '/admin/login?from=admin%2Fpayments%23init');
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreateFailsIfCsrfIsInvalid(string $email, int $amount): void
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/new', [
            'csrf' => 'not the token',
            'email' => $email,
            'amount' => $amount,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
    }

    public function testShowRendersCorrectly(): void
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create();

        $response = $this->appRun('GET', '/admin/payments/' . $payment->id);

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'admin/payments/show.phtml');
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

        $this->assertResponseCode($response, 302, '/admin/login?from=admin%2Fpayments%23index');
    }

    public function testConfirmRendersCorrectly(): void
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'is_paid' => false,
        ]);

        $response = $this->appRun('POST', "/admin/payments/{$payment->id}/confirm", [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=payment_confirmed');
        /** @var models\Payment */
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
            'csrf' => \Minz\Csrf::generate(),
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
            'csrf' => \Minz\Csrf::generate(),
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
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin/login?from=admin%2Fpayments%23index');
        /** @var models\Payment */
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
        /** @var models\Payment */
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
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=payment_deleted');
        $this->assertFalse(models\Payment::exists($payment->id));
    }

    public function testDestroyFailsIfInvalidId(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/payments/invalid/destroy', [
            'csrf' => \Minz\Csrf::generate(),
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
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin/login?from=admin%2Fpayments%23index');
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
            'csrf' => \Minz\Csrf::generate(),
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
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Ce paiement est associé à une facture');
        $this->assertTrue(models\Payment::exists($payment->id));
    }

    /**
     * @return array<array{string, int}>
     */
    public function createProvider(): array
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
