<?php

namespace Website;

class InvoicesTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
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
    public function testSendPdfSucceedsAndSendsAnEmail($completed_at, $invoice_number)
    {
        $email = $this->fake('email');
        $payment_id = $this->create('payment', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => $invoice_number,
            'email' => $email,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('CLI', '/invoices/' . $payment_id . '/email');

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
        $response = $this->appRun('CLI', '/invoices/non_existing/email');

        $this->assertResponse($response, 404, 'Le paiement n’existe pas.');
        $this->assertEmailsCount(0);
    }

    /**
     * @dataProvider completedParametersProvider
     */
    public function testSendPdfFailsIfNoInvoiceNumber($completed_at, $invoice_number)
    {
        $payment_id = $this->create('payment', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'invoice_number' => null,
        ]);

        $response = $this->appRun('CLI', '/invoices/' . $payment_id . '/email');

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
