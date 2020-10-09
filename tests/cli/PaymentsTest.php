<?php

namespace Website\cli;

use Website\models;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;
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

    public function testCompleteCompletesPaidButNotCompletedPayments()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'completed_at' => null,
            'is_paid' => 1,
        ]);

        $response = $this->appRun('CLI', '/payments/complete');

        $this->assertResponse($response, 200, '1 payments completed');
        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertSame($now->getTimestamp(), $payment->completed_at->getTimestamp());
    }

    /**
     * @dataProvider frequencyProvider
     */
    public function testCompleteExtendsSubscriptionIfAccountIsAttached($frequency)
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $account_dao = new models\dao\Account();
        $account_id = $this->create('account', [
            'expired_at' => \Minz\Time::now()->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $payment_id = $this->create('payment', [
            'completed_at' => null,
            'is_paid' => 1,
            'account_id' => $account_id,
            'frequency' => $frequency,
        ]);
        if ($frequency === 'month') {
            $expected_expired_at = \Minz\Time::fromNow(1, 'month');
        } else {
            $expected_expired_at = \Minz\Time::fromNow(1, 'year');
        }

        $response = $this->appRun('CLI', '/payments/complete');

        $account = new models\Account($account_dao->find($account_id));
        $this->assertSame($expected_expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }

    public function testCompleteCreatesAnInvoice()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'completed_at' => null,
            'is_paid' => 1,
        ]);

        $response = $this->appRun('CLI', '/payments/complete');

        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertNotNull($payment->invoice_number);
        $this->assertTrue($payment->invoiceExists());
    }

    public function testCompleteSendsAnEmail()
    {
        $email = $this->fake('email');
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'email' => $email,
            'completed_at' => null,
            'is_paid' => 1,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('CLI', '/payments/complete');

        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertEmailSubject($email_sent, '[Flus] Reçu pour votre paiement');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'Votre paiement pour Flus a bien été pris en compte');
        $attachments = $email_sent->getAttachments();
        $this->assertSame(1, count($attachments));
    }

    public function testCompleteDoesNothingIfAlreadyCompleted()
    {
        $completed_at = $this->fake('dateTime');
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
            'is_paid' => 1,
        ]);

        $response = $this->appRun('CLI', '/payments/complete');

        $this->assertResponse($response, 200, '0 payments completed');
        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertSame($completed_at->getTimestamp(), $payment->completed_at->getTimestamp());
    }

    public function testCompleteDoesNothingIfNotIsPaid()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'completed_at' => null,
            'is_paid' => 0,
        ]);

        $response = $this->appRun('CLI', '/payments/complete');

        $this->assertResponse($response, 200, '0 payments completed');
        $payment = new models\Payment($payment_dao->find($payment_id));
        $this->assertNull($payment->completed_at);
    }

    public function frequencyProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                $faker->randomElement(['month', 'year']),
            ];
        }

        return $datasets;
    }
}
