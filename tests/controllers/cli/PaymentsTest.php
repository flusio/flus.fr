<?php

namespace Website\controllers\cli;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;
use Website\models;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\MailerAsserts;

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

    public function testCompleteCompletesPaidButNotCompletedPayments(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $payment = PaymentFactory::create([
            'completed_at' => null,
            'is_paid' => true,
            'frequency' => 'year',
        ]);

        $response = $this->appRun('CLI', '/payments/complete');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 payments completed');
        /** @var models\Payment */
        $payment = $payment->reload();
        $this->assertNotNull($payment->completed_at);
        $this->assertSame($now->getTimestamp(), $payment->completed_at->getTimestamp());
    }

    /**
     * @dataProvider frequencyProvider
     */
    public function testCompleteExtendsSubscriptionIfAccountIsAttached(string $frequency): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $account = AccountFactory::create([
            'expired_at' => \Minz\Time::now(),
        ]);
        $payment = PaymentFactory::create([
            'type' => 'subscription',
            'completed_at' => null,
            'is_paid' => true,
            'account_id' => $account->id,
            'frequency' => $frequency,
        ]);
        if ($frequency === 'month') {
            $expected_expired_at = \Minz\Time::fromNow(1, 'month');
        } else {
            $expected_expired_at = \Minz\Time::fromNow(1, 'year');
        }

        $response = $this->appRun('CLI', '/payments/complete');

        /** @var models\Account */
        $account = $account->reload();
        $this->assertSame($expected_expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }

    public function testCompleteDontExtendsSubscriptionIfNotSubscription(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $expired_at = \Minz\Time::now();
        $account = AccountFactory::create([
            'expired_at' => $expired_at,
        ]);
        $type = $this->fake('randomElement', ['common_pot', 'credit']);
        $payment = PaymentFactory::create([
            'type' => $type,
            'completed_at' => null,
            'is_paid' => true,
            'account_id' => $account->id,
        ]);

        $response = $this->appRun('CLI', '/payments/complete');

        /** @var models\Account */
        $account = $account->reload();
        $this->assertSame($expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }

    public function testCompleteCreatesAnInvoice(): void
    {
        $payment = PaymentFactory::create([
            'completed_at' => null,
            'is_paid' => true,
            'frequency' => 'year',
        ]);

        $response = $this->appRun('CLI', '/payments/complete');

        /** @var models\Payment */
        $payment = $payment->reload();
        $this->assertNotNull($payment->invoice_number);
        $this->assertTrue($payment->invoiceExists());
    }

    public function testCompleteSendsAnEmail(): void
    {
        $email = $this->fake('email');
        $account = AccountFactory::create([
            'email' => $email,
        ]);
        $payment = PaymentFactory::create([
            'account_id' => $account->id,
            'completed_at' => null,
            'is_paid' => true,
            'frequency' => 'year',
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('CLI', '/payments/complete');

        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertNotNull($email_sent);
        $this->assertEmailSubject($email_sent, '[Flus] Reçu pour votre paiement');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'Votre paiement pour Flus a bien été pris en compte');
        $attachments = $email_sent->getAttachments();
        $this->assertSame(1, count($attachments));
    }

    public function testCompleteDoesNothingIfAlreadyCompleted(): void
    {
        $completed_at = $this->fake('dateTime');
        $payment = PaymentFactory::create([
            'completed_at' => $completed_at,
            'is_paid' => true,
        ]);

        $response = $this->appRun('CLI', '/payments/complete');

        $this->assertResponseCode($response, 200);
        /** @var models\Payment */
        $payment = $payment->reload();
        $this->assertNotNull($payment->completed_at);
        $this->assertSame($completed_at->getTimestamp(), $payment->completed_at->getTimestamp());
    }

    public function testCompleteDoesNothingIfNotIsPaid(): void
    {
        $payment = PaymentFactory::create([
            'completed_at' => null,
            'is_paid' => false,
        ]);

        $response = $this->appRun('CLI', '/payments/complete');

        $this->assertResponseCode($response, 200);
        /** @var models\Payment */
        $payment = $payment->reload();
        $this->assertNull($payment->completed_at);
    }

    /**
     * @return array<array{'month'|'year'}>
     */
    public function frequencyProvider(): array
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
