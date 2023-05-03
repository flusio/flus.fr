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
    public static function dropInvoices()
    {
        $files = glob(\Minz\Configuration::$data_path . '/invoices/*');
        foreach ($files as $file) {
            @unlink($file);
        }
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testDownloadPdfWithAuthenticatedAdminRendersAPdf($completed_at, $invoice_number)
    {
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
    public function testDownloadPdfWithAuthenticatedUserRendersAPdf($completed_at, $invoice_number)
    {
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

    public function testDownloadPdfWithNonExistingPaymentReturnsNotFound()
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/invoices/not-an-id/pdf');

        $this->assertResponseCode($response, 404);
    }

    public function testDownloadPdfWithPaymentWithNoInvoiceNumberReturnsNotFound()
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
    public function testDownloadPdfWithMissingAuthenticationReturnsUnauthorized($completed_at, $invoice_number)
    {
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
    public function testDownloadPdfWithAuthenticatedNotOwningUserReturnsUnauthorized($completed_at, $invoice_number)
    {
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

    public function completedParametersProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $date = $faker->dateTime;
            $datasets[] = [
                $date,
                $date->format('Y-m') . sprintf('-%04d', $faker->randomNumber(4)),
            ];
        }

        return $datasets;
    }
}
