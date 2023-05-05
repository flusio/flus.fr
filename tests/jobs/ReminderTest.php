<?php

namespace Website\jobs;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;
use tests\factories\PotUsageFactory;
use Website\models;

class ReminderTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\MailerAsserts;

    /**
     * @beforeClass
     */
    public static function setupRouter(): void
    {
        $router = \Website\Router::loadCli();
        \Minz\Engine::init($router);
    }

    public function testPerformSendsEmailAt7DaysBeforeExpiration(): void
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

        $reminder = new Reminder();
        $reminder->perform();

        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertNotNull($email_sent);
        $this->assertEmailSubject($email_sent, '[Flus] Votre abonnement arrive à échéance');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'En vous remerciant de votre soutien');
    }

    public function testPerformSendsEmailAt2DaysBeforeExpiration(): void
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

        $reminder = new Reminder();
        $reminder->perform();

        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertNotNull($email_sent);
        $this->assertEmailSubject($email_sent, '[Flus] Votre abonnement arrive à échéance');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'En vous remerciant de votre soutien');
    }

    public function testPerformSendsEmailTheDayAfterExpiration(): void
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

        $reminder = new Reminder();
        $reminder->perform();

        $this->assertEmailsCount(1);
        $email_sent = \Minz\Tests\Mailer::take();
        $this->assertNotNull($email_sent);
        $this->assertEmailSubject($email_sent, '[Flus] Votre abonnement a expiré');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, 'En vous remerciant de votre soutien');
    }

    public function testPerformSendsDifferentEmailsToDifferentPeople(): void
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

        $reminder = new Reminder();
        $reminder->perform();

        $this->assertEmailsCount(2);
        $email_sent_1 = \Minz\Tests\Mailer::take(0);
        $this->assertNotNull($email_sent_1);
        $this->assertEmailEqualsTo($email_sent_1, [$email_1]);
        $email_sent_2 = \Minz\Tests\Mailer::take(1);
        $this->assertNotNull($email_sent_2);
        $this->assertEmailEqualsTo($email_sent_2, [$email_2]);
    }

    public function testPerformDoesNotSendEmailIfReminderIsFalse(): void
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        AccountFactory::create([
            'reminder' => false,
            'expired_at' => \Minz\Time::fromNow(7, 'days'),
            'last_sync_at' => \Minz\Time::now(),
            'email' => $email,
        ]);

        $reminder = new Reminder();
        $reminder->perform();

        $this->assertEmailsCount(0);
    }

    public function testPerformDoesNotSendEmailIfAccountIsFree(): void
    {
        $this->freeze($this->fake('dateTime'));
        $email = $this->fake('email');
        AccountFactory::create([
            'reminder' => true,
            'expired_at' => new \DateTimeImmutable('@0'),
            'last_sync_at' => \Minz\Time::now(),
            'email' => $email,
        ]);

        $reminder = new Reminder();
        $reminder->perform();

        $this->assertEmailsCount(0);
    }

    public function testPerformDoesNotSendEmailIfAccountIsNotSynced(): void
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

        $reminder = new Reminder();
        $reminder->perform();

        $this->assertEmailsCount(0);
    }
}
