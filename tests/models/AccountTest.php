<?php

namespace Website\models;

class AccountTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\TimeHelper;

    public function testExtendSubscriptionAdds1MonthToCurrentIfExpirationInFuture()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $expired_at = \Minz\Time::fromNow($this->fake('randomDigitNotNull'), 'days');
        $account = new Account($this->fake('email'));
        $account->expired_at = $expired_at;

        $account->extendSubscription('month');

        $expected_expired_at = $expired_at->modify('+1 month');
        $this->assertSame($expected_expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }

    public function testExtendSubscriptionAdds1YearToCurrentIfExpirationInFuture()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $expired_at = \Minz\Time::fromNow($this->fake('randomDigitNotNull'), 'days');
        $account = new Account($this->fake('email'));
        $account->expired_at = $expired_at;

        $account->extendSubscription('year');

        $expected_expired_at = $expired_at->modify('+1 year');
        $this->assertSame($expected_expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }

    public function testExtendSubscriptionAdds1MonthToTodayIfExpirationInPast()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $expired_at = \Minz\Time::ago($this->fake('randomDigitNotNull'), 'days');
        $account = new Account($this->fake('email'));
        $account->expired_at = $expired_at;

        $account->extendSubscription('month');

        $expected_expired_at = $now->modify('+1 month');
        $this->assertSame($expected_expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }

    public function testExtendSubscriptionAdds1YearToTodayIfExpirationInPast()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $expired_at = \Minz\Time::ago($this->fake('randomDigitNotNull'), 'days');
        $account = new Account($this->fake('email'));
        $account->expired_at = $expired_at;

        $account->extendSubscription('year');

        $expected_expired_at = $now->modify('+1 year');
        $this->assertSame($expected_expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }

    public function testExtendSubscriptionDoesNothingIfFreeAccount()
    {
        $frequency = $this->fake('randomElement', ['month', 'year']);
        $expired_at = new \DateTimeImmutable('@0');
        $account = new Account($this->fake('email'));
        $account->expired_at = $expired_at;

        $account->extendSubscription($frequency);

        $this->assertSame($expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }
}
