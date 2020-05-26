<?php

namespace Website;

class InvoicesTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\MailerAsserts;

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
        $payment_id = $this->create('payments', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => $invoice_number,
        ]);

        $request = new \Minz\Request('GET', '/invoices/pdf/' . $payment_id, [], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $response = self::$application->run($request);

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
        $this->login();

        $payment_id = $this->create('payments', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => $invoice_number,
        ]);

        $request = new \Minz\Request('GET', '/invoices/pdf/' . $payment_id);

        $response = self::$application->run($request);

        $expected_filename = "facture_{$invoice_number}.pdf";
        $this->assertResponse($response, 200, null, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $expected_filename . '"',
        ]);
    }

    public function testDownloadPdfWithNonExistingPaymentReturnsNotFound()
    {
        $request = new \Minz\Request('GET', '/invoices/pdf/not_an_existing_id', [], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 404);
    }

    public function testDownloadPdfWithPaymentWithNoInvoiceNumberReturnsNotFound()
    {
        $payment_id = $this->create('payments', [
            'invoice_number' => null,
        ]);

        $request = new \Minz\Request('GET', '/invoices/pdf/' . $payment_id, [], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $response = self::$application->run($request);

        $this->assertResponse($response, 404);
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testDownloadPdfWithMissingAuthenticationReturnsUnauthorized($completed_at, $invoice_number)
    {
        $payment_id = $this->create('payments', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => $invoice_number,
        ]);

        $request = new \Minz\Request('GET', '/invoices/pdf/' . $payment_id);

        $response = self::$application->run($request);

        $this->assertResponse($response, 401);
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testSendPdfSucceedsAndSendsAnEmail($completed_at, $invoice_number)
    {
        $faker = \Faker\Factory::create();
        $email = $faker->email;
        $payment_id = $this->create('payments', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => $invoice_number,
            'email' => $email,
        ]);

        $this->assertEmailsCount(0);

        $request = new \Minz\Request('CLI', '/invoices/' . $payment_id . '/email');

        $response = self::$application->run($request);

        $this->assertResponse($response, 200, "La facture {$invoice_number} a été envoyée à l’adresse {$email}.");
        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertEmailSubject($email_sent, '[Flus] Reçu pour votre paiement');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'Votre paiement pour Flus a bien été pris en compte');
        $attachments = $email_sent->getAttachments();
        $this->assertSame(1, count($attachments));
    }

    public function testSendPdfFailsIfNonExistingPayment()
    {
        $request = new \Minz\Request('CLI', '/invoices/non_existing/email');

        $response = self::$application->run($request);

        $this->assertResponse($response, 404, 'Le paiement n’existe pas.');
        $this->assertEmailsCount(0);
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testSendPdfFailsIfNoInvoiceNumber($completed_at, $invoice_number)
    {
        $payment_id = $this->create('payments', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => null,
        ]);

        $request = new \Minz\Request('CLI', '/invoices/' . $payment_id . '/email');

        $response = self::$application->run($request);

        $this->assertResponse($response, 400, 'Ce paiement n’a pas de numéro de facture associé.');
        $this->assertEmailsCount(0);
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
