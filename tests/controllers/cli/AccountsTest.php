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
            'email' => $email_1,
        ]);
        $this->create('account', [
            'reminder' => true,
            'expired_at' => \Minz\Time::ago(1, 'day')->format(\Minz\Model::DATETIME_FORMAT),
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
            'email' => $email,
        ]);

        $response = $this->appRun('cli', '/accounts/remind');

        $this->assertResponseCode($response, 200);
        $this->assertEmailsCount(0);
    }
}
