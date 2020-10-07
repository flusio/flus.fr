<?php

namespace Website\api;

class InvoicesTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
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
    public function testDownloadPdfRendersAPdf($completed_at, $invoice_number)
    {
        $payment_id = $this->create('payment', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => $invoice_number,
        ]);

        $response = $this->appRun('GET', '/invoices/pdf/' . $payment_id, [], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $expected_filename = "facture_{$invoice_number}.pdf";
        $this->assertResponse($response, 200, null, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $expected_filename . '"',
        ]);
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testDownloadPdfWithAuthenticatedAdminRendersAPdf($completed_at, $invoice_number)
    {
        $this->loginAdmin();

        $payment_id = $this->create('payment', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => $invoice_number,
        ]);

        $response = $this->appRun('GET', '/invoices/pdf/' . $payment_id);

        $expected_filename = "facture_{$invoice_number}.pdf";
        $this->assertResponse($response, 200, null, [
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

        $payment_id = $this->create('payment', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => $invoice_number,
            'account_id' => $user['account_id'],
        ]);

        $response = $this->appRun('GET', '/invoices/pdf/' . $payment_id);

        $expected_filename = "facture_{$invoice_number}.pdf";
        $this->assertResponse($response, 200, null, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $expected_filename . '"',
        ]);
    }

    public function testDownloadPdfWithNonExistingPaymentReturnsNotFound()
    {
        $response = $this->appRun('GET', '/invoices/pdf/not_an_existing_id', [], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 404);
    }

    public function testDownloadPdfWithPaymentWithNoInvoiceNumberReturnsNotFound()
    {
        $payment_id = $this->create('payment', [
            'invoice_number' => null,
        ]);

        $response = $this->appRun('GET', '/invoices/pdf/' . $payment_id, [], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 404);
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testDownloadPdfWithMissingAuthenticationReturnsUnauthorized($completed_at, $invoice_number)
    {
        $payment_id = $this->create('payment', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => $invoice_number,
        ]);

        $response = $this->appRun('GET', '/invoices/pdf/' . $payment_id);

        $this->assertResponse($response, 401);
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testDownloadPdfWithAuthenticatedNotOwningUserReturnsUnauthorized($completed_at, $invoice_number)
    {
        $user = $this->loginUser();
        $account_id = $this->create('account');
        $payment_id = $this->create('payment', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => $invoice_number,
            'account_id' => $account_id,
        ]);

        $response = $this->appRun('GET', '/invoices/pdf/' . $payment_id);

        $this->assertResponse($response, 401);
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
