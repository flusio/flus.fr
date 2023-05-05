<?php

namespace Website\cli;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;
use tests\factories\PotUsageFactory;
use Website\models;

class AccountsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\MailerAsserts;

    public function testRemindSendsEmailAt7DaysBeforeExpiration(): void
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        AccountFactory::create([
            'reminder' => true,
            'expired_at' => \Minz\Time::fromNow(7, 'days'),
            'last_sync_at' => \Minz\Time::now(),
            'email' => $email,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('CLI', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 reminders sent');
        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertNotNull($email_sent);
        $this->assertEmailSubject($email_sent, '[Flus] Votre abonnement arrive à échéance');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'En vous remerciant de votre soutien');
    }

    public function testRemindSendsEmailAt2DaysBeforeExpiration(): void
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        AccountFactory::create([
            'reminder' => true,
            'expired_at' => \Minz\Time::fromNow(2, 'days'),
            'last_sync_at' => \Minz\Time::now(),
            'email' => $email,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('CLI', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 reminders sent');
        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertNotNull($email_sent);
        $this->assertEmailSubject($email_sent, '[Flus] Votre abonnement arrive à échéance');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'En vous remerciant de votre soutien');
    }

    public function testRemindSendsEmailTheDayAfterExpiration(): void
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        AccountFactory::create([
            'reminder' => true,
            'expired_at' => \Minz\Time::ago(1, 'day'),
            'last_sync_at' => \Minz\Time::now(),
            'email' => $email,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('CLI', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 reminders sent');
        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertNotNull($email_sent);
        $this->assertEmailSubject($email_sent, '[Flus] Votre abonnement a expiré');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'En vous remerciant de votre soutien');
    }

    public function testRemindSendsDifferentEmailsToDifferentPeople(): void
    {
        $this->freeze($this->fake('dateTime'));
        $email_1 = $this->fakeUnique('email');
        $email_2 = $this->fakeUnique('email');
        AccountFactory::create([
            'reminder' => true,
            'expired_at' => \Minz\Time::ago(1, 'day'),
            'last_sync_at' => \Minz\Time::now(),
            'email' => $email_1,
        ]);
        AccountFactory::create([
            'reminder' => true,
            'expired_at' => \Minz\Time::ago(1, 'day'),
            'last_sync_at' => \Minz\Time::now(),
            'email' => $email_2,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('CLI', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '2 reminders sent');
        $this->assertEmailsCount(2);
        $email_sent_1 = \Minz\Tests\Mailer::take(0);
        $this->assertNotNull($email_sent_1);
        $this->assertEmailEqualsTo($email_sent_1, [$email_1]);
        $email_sent_2 = \Minz\Tests\Mailer::take(1);
        $this->assertNotNull($email_sent_2);
        $this->assertEmailEqualsTo($email_sent_2, [$email_2]);
    }

    public function testRemindDoesNotSendEmailIfReminderIsFalse(): void
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        AccountFactory::create([
            'reminder' => false,
            'expired_at' => \Minz\Time::fromNow(7, 'days'),
            'last_sync_at' => \Minz\Time::now(),
            'email' => $email,
        ]);

        $response = $this->appRun('CLI', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertEmailsCount(0);
    }

    public function testRemindDoesNotSendEmailIfAccountIsFree(): void
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        AccountFactory::create([
            'reminder' => true,
            'expired_at' => new \DateTimeImmutable('@0'),
            'last_sync_at' => \Minz\Time::now(),
            'email' => $email,
        ]);

        $response = $this->appRun('CLI', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertEmailsCount(0);
    }

    public function testRemindDoesNotSendEmailIfAccountIsNotSynced(): void
    {
        $this->freeze($this->fake('dateTime'));
        $hours = $this->fake('numberBetween', 25, 90);
        $email = $this->fake('email');
        AccountFactory::create([
            'reminder' => true,
            'expired_at' => \Minz\Time::fromNow(7, 'days'),
            'last_sync_at' => \Minz\Time::ago($hours, 'hours'),
            'email' => $email,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('CLI', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertEmailsCount(0);
    }

    public function testClearRemovesNonSyncAccounts(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 3, 30);
        $account = AccountFactory::create([
            'last_sync_at' => \Minz\Time::ago($days, 'days'),
        ]);

        $response = $this->appRun('CLI', '/accounts/clear');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 accounts have been deleted.');
        $this->assertFalse(models\Account::exists($account->id));
    }

    public function testClearRemovesAccountsNeverSync(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $account = AccountFactory::create([
            'last_sync_at' => null,
        ]);

        $response = $this->appRun('CLI', '/accounts/clear');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 accounts have been deleted.');
        $this->assertFalse(models\Account::exists($account->id));
    }

    public function testClearMovesPaymentsAndPotUsagesOfNonSyncAccounts(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 3, 30);
        $default_account = models\Account::defaultAccount();
        $account = AccountFactory::create([
            'last_sync_at' => \Minz\Time::ago($days, 'days'),
        ]);
        $payment = PaymentFactory::create([
            'account_id' => $account->id,
        ]);
        $pot_usage = PotUsageFactory::create([
            'account_id' => $account->id,
        ]);

        $response = $this->appRun('CLI', '/accounts/clear');

        $this->assertResponseCode($response, 200);
        /** @var models\Payment */
        $payment = $payment->reload();
        /** @var models\PotUsage */
        $pot_usage = $pot_usage->reload();
        $this->assertSame($default_account->id, $payment->account_id);
        $this->assertSame($default_account->id, $pot_usage->account_id);
    }

    public function testClearKeepsSyncAccounts(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 0, 2);
        $account = AccountFactory::create([
            'last_sync_at' => \Minz\Time::ago($days, 'days'),
        ]);

        $response = $this->appRun('CLI', '/accounts/clear');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '0 accounts have been deleted.');
        $this->assertTrue(models\Account::exists($account->id));
    }

    public function testClearDoesNotMovePaymentsAndPotUsagesOfSyncAccounts(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 0, 2);
        $default_account = models\Account::defaultAccount();
        $account = AccountFactory::create([
            'last_sync_at' => \Minz\Time::ago($days, 'days'),
        ]);
        $payment = PaymentFactory::create([
            'account_id' => $account->id,
        ]);
        $pot_usage = PotUsageFactory::create([
            'account_id' => $account->id,
        ]);

        $response = $this->appRun('CLI', '/accounts/clear');

        $this->assertResponseCode($response, 200);
        /** @var models\Payment */
        $payment = $payment->reload();
        /** @var models\PotUsage */
        $pot_usage = $pot_usage->reload();
        $this->assertSame($account->id, $payment->account_id);
        $this->assertSame($account->id, $pot_usage->account_id);
    }
}
