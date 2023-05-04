<?php

namespace Website\controllers;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;

class InvoicesTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;

    /**
     * @afterClass
     */
    public static function dropInvoices(): void
    {
        $files = @glob(\Minz\Configuration::$data_path . '/invoices/*');

        assert($files !== false);

        foreach ($files as $file) {
            @unlink($file);
        }
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testDownloadPdfWithAuthenticatedAdminRendersAPdf(
        \DateTimeImmutable $completed_at,
        string $invoice_number
    ): void {
        $this->loginAdmin();

        $payment = PaymentFactory::create([
            'completed_at' => $completed_at,
            'invoice_number' => $invoice_number,
        ]);

        $response = $this->appRun('GET', "/invoices/{$payment->id}/pdf");

        $expected_filename = "facture_{$invoice_number}.pdf";
        $this->assertResponseCode($response, 200);
        $this->assertResponseHeaders($response, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $expected_filename . '"',
        ]);
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testDownloadPdfWithAuthenticatedUserRendersAPdf(
        \DateTimeImmutable $completed_at,
        string $invoice_number
    ): void {
        $user = $this->loginUser();

        $payment = PaymentFactory::create([
            'completed_at' => $completed_at,
            'invoice_number' => $invoice_number,
            'account_id' => $user['account_id'],
        ]);

        $response = $this->appRun('GET', "/invoices/{$payment->id}/pdf");

        $expected_filename = "facture_{$invoice_number}.pdf";
        $this->assertResponseCode($response, 200);
        $this->assertResponseHeaders($response, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $expected_filename . '"',
        ]);
    }

    public function testDownloadPdfWithNonExistingPaymentReturnsNotFound(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/invoices/not-an-id/pdf');

        $this->assertResponseCode($response, 404);
    }

    public function testDownloadPdfWithPaymentWithNoInvoiceNumberReturnsNotFound(): void
    {
        $this->loginAdmin();
        $payment = PaymentFactory::create([
            'invoice_number' => null,
        ]);

        $response = $this->appRun('GET', "/invoices/{$payment->id}/pdf");

        $this->assertResponseCode($response, 404);
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testDownloadPdfWithMissingAuthenticationReturnsUnauthorized(
        \DateTimeImmutable $completed_at,
        string $invoice_number
    ): void {
        $payment = PaymentFactory::create([
            'completed_at' => $completed_at,
            'invoice_number' => $invoice_number,
        ]);

        $response = $this->appRun('GET', "/invoices/{$payment->id}/pdf");

        $this->assertResponseCode($response, 401);
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testDownloadPdfWithAuthenticatedNotOwningUserReturnsUnauthorized(
        \DateTimeImmutable $completed_at,
        string $invoice_number
    ): void {
        $user = $this->loginUser();
        $account = AccountFactory::create();
        $payment = PaymentFactory::create([
            'completed_at' => $completed_at,
            'invoice_number' => $invoice_number,
            'account_id' => $account->id,
        ]);

        $response = $this->appRun('GET', "/invoices/{$payment->id}/pdf");

        $this->assertResponseCode($response, 401);
    }

    /**
     * @return array<array{\DateTimeImmutable, string}>
     */
    public function completedParametersProvider(): array
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $date = \DateTimeImmutable::createFromMutable($faker->dateTime);
            $datasets[] = [
                $date,
                $date->format('Y-m') . sprintf('-%04d', $faker->randomNumber(4)),
            ];
        }

        return $datasets;
    }
}
