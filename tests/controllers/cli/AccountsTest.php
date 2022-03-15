<?php

namespace Website\controllers\cli;

use Website\models;

class AccountsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\MailerAsserts;

    public function testRemindSendsEmailAt7DaysBeforeExpiration()
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        $this->create('account', [
            'reminder' => true,
            'expired_at' => \Minz\Time::fromNow(7, 'days')->format(\Minz\Model::DATETIME_FORMAT),
            'last_sync_at' => \Minz\Time::now()->format(\Minz\Model::DATETIME_FORMAT),
            'email' => $email,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('cli', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 reminders sent');
        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertEmailSubject($email_sent, '[Flus] Votre abonnement arrive à échéance');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'En vous remerciant de votre soutien');
    }

    public function testRemindSendsEmailAt2DaysBeforeExpiration()
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        $this->create('account', [
            'reminder' => true,
            'expired_at' => \Minz\Time::fromNow(2, 'days')->format(\Minz\Model::DATETIME_FORMAT),
            'last_sync_at' => \Minz\Time::now()->format(\Minz\Model::DATETIME_FORMAT),
            'email' => $email,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('cli', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 reminders sent');
        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertEmailSubject($email_sent, '[Flus] Votre abonnement arrive à échéance');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'En vous remerciant de votre soutien');
    }

    public function testRemindSendsEmailTheDayAfterExpiration()
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        $this->create('account', [
            'reminder' => true,
            'expired_at' => \Minz\Time::ago(1, 'day')->format(\Minz\Model::DATETIME_FORMAT),
            'last_sync_at' => \Minz\Time::now()->format(\Minz\Model::DATETIME_FORMAT),
            'email' => $email,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('cli', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 reminders sent');
        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertEmailSubject($email_sent, '[Flus] Votre abonnement a expiré');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'En vous remerciant de votre soutien');
    }

    public function testRemindSendsDifferentEmailsToDifferentPeople()
    {
        $this->freeze($this->fake('dateTime'));
        $email_1 = $this->fakeUnique('email');
        $email_2 = $this->fakeUnique('email');
        $this->create('account', [
            'reminder' => true,
            'expired_at' => \Minz\Time::ago(1, 'day')->format(\Minz\Model::DATETIME_FORMAT),
            'last_sync_at' => \Minz\Time::now()->format(\Minz\Model::DATETIME_FORMAT),
            'email' => $email_1,
        ]);
        $this->create('account', [
            'reminder' => true,
            'expired_at' => \Minz\Time::ago(1, 'day')->format(\Minz\Model::DATETIME_FORMAT),
            'last_sync_at' => \Minz\Time::now()->format(\Minz\Model::DATETIME_FORMAT),
            'email' => $email_2,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('cli', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '2 reminders sent');
        $this->assertEmailsCount(2);
        $email_sent_1 = \Minz\Tests\Mailer::take(0);
        $this->assertEmailEqualsTo($email_sent_1, [$email_1]);
        $email_sent_2 = \Minz\Tests\Mailer::take(1);
        $this->assertEmailEqualsTo($email_sent_2, [$email_2]);
    }

    public function testRemindDoesNotSendEmailIfReminderIsFalse()
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        $this->create('account', [
            'reminder' => 0,
            'expired_at' => \Minz\Time::fromNow(7, 'days')->format(\Minz\Model::DATETIME_FORMAT),
            'last_sync_at' => \Minz\Time::now()->format(\Minz\Model::DATETIME_FORMAT),
            'email' => $email,
        ]);

        $response = $this->appRun('cli', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertEmailsCount(0);
    }

    public function testRemindDoesNotSendEmailIfAccountIsFree()
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        $this->create('account', [
            'reminder' => true,
            'expired_at' => (new \DateTime('1970-01-01'))->format(\Minz\Model::DATETIME_FORMAT),
            'last_sync_at' => \Minz\Time::now()->format(\Minz\Model::DATETIME_FORMAT),
            'email' => $email,
        ]);

        $response = $this->appRun('cli', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertEmailsCount(0);
    }

    public function testRemindDoesNotSendEmailIfAccountIsNotSynced()
    {
        $this->freeze($this->fake('dateTime'));
        $hours = $this->fake('numberBetween', 25, 90);
        $email = $this->fake('email');
        $this->create('account', [
            'reminder' => true,
            'expired_at' => \Minz\Time::fromNow(7, 'days')->format(\Minz\Model::DATETIME_FORMAT),
            'last_sync_at' => \Minz\Time::ago($hours, 'hours')->format(\Minz\Model::DATETIME_FORMAT),
            'email' => $email,
        ]);

        $this->assertEmailsCount(0);

        $response = $this->appRun('cli', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertEmailsCount(0);
    }

    public function testClearRemovesNonSyncAccounts()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 3, 30);
        $account_id = $this->create('account', [
            'last_sync_at' => \Minz\Time::ago($days, 'days')->format(\Minz\Model::DATETIME_FORMAT),
        ]);

        $response = $this->appRun('cli', '/accounts/clear');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 accounts have been deleted.');
        $this->assertFalse(models\Account::exists($account_id));
    }

    public function testClearRemovesAccountsNeverSync()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $account_id = $this->create('account', [
            'last_sync_at' => null,
        ]);

        $response = $this->appRun('cli', '/accounts/clear');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '1 accounts have been deleted.');
        $this->assertFalse(models\Account::exists($account_id));
    }

    public function testClearMovesPaymentsAndPotUsagesOfNonSyncAccounts()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 3, 30);
        $default_account = models\Account::defaultAccount();
        $account_id = $this->create('account', [
            'last_sync_at' => \Minz\Time::ago($days, 'days')->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $payment_id = $this->create('payment', [
            'account_id' => $account_id,
        ]);
        $pot_usage_id = $this->create('pot_usage', [
            'account_id' => $account_id,
        ]);

        $response = $this->appRun('cli', '/accounts/clear');

        $this->assertResponseCode($response, 200);
        $payment = models\Payment::find($payment_id);
        $pot_usage = models\PotUsage::find($pot_usage_id);
        $this->assertSame($default_account->id, $payment->account_id);
        $this->assertSame($default_account->id, $pot_usage->account_id);
    }

    public function testClearKeepsSyncAccounts()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 0, 2);
        $account_id = $this->create('account', [
            'last_sync_at' => \Minz\Time::ago($days, 'days')->format(\Minz\Model::DATETIME_FORMAT),
        ]);

        $response = $this->appRun('cli', '/accounts/clear');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, '0 accounts have been deleted.');
        $this->assertTrue(models\Account::exists($account_id));
    }

    public function testClearDoesNotMovePaymentsAndPotUsagesOfSyncAccounts()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 0, 2);
        $default_account = models\Account::defaultAccount();
        $account_id = $this->create('account', [
            'last_sync_at' => \Minz\Time::ago($days, 'days')->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $payment_id = $this->create('payment', [
            'account_id' => $account_id,
        ]);
        $pot_usage_id = $this->create('pot_usage', [
            'account_id' => $account_id,
        ]);

        $response = $this->appRun('cli', '/accounts/clear');

        $this->assertResponseCode($response, 200);
        $payment = models\Payment::find($payment_id);
        $pot_usage = models\PotUsage::find($pot_usage_id);
        $this->assertSame($account_id, $payment->account_id);
        $this->assertSame($account_id, $pot_usage->account_id);
    }
}
