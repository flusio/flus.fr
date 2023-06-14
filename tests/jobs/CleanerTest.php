<?php

namespace Website\jobs;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;
use tests\factories\PotUsageFactory;
use Website\models;

class CleanerTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\TimeHelper;

    public function testPerformRemovesNonSyncAccounts(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 3, 30);
        $account = AccountFactory::create([
            'last_sync_at' => \Minz\Time::ago($days, 'days'),
        ]);

        $cleaner = new Cleaner();
        $cleaner->perform();

        $this->assertFalse(models\Account::exists($account->id));
    }

    public function testPerformRemovesAccountsNeverSync(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $account = AccountFactory::create([
            'last_sync_at' => null,
        ]);

        $cleaner = new Cleaner();
        $cleaner->perform();

        $this->assertFalse(models\Account::exists($account->id));
    }

    public function testPerformMovesPaymentsAndPotUsagesOfNonSyncAccounts(): void
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

        $cleaner = new Cleaner();
        $cleaner->perform();

        /** @var models\Payment */
        $payment = $payment->reload();
        /** @var models\PotUsage */
        $pot_usage = $pot_usage->reload();
        $this->assertSame($default_account->id, $payment->account_id);
        $this->assertSame($default_account->id, $pot_usage->account_id);
    }

    public function testPerformKeepsSyncAccounts(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 0, 2);
        $account = AccountFactory::create([
            'last_sync_at' => \Minz\Time::ago($days, 'days'),
        ]);

        $cleaner = new Cleaner();
        $cleaner->perform();

        $this->assertTrue(models\Account::exists($account->id));
    }

    public function testPerformKeepsUnsyncButManagedAccounts(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $days = $this->fake('numberBetween', 3, 30);
        $account = AccountFactory::create([
            'last_sync_at' => \Minz\Time::ago($days, 'days'),
            'managed_by_id' => AccountFactory::create()->id,
        ]);

        $cleaner = new Cleaner();
        $cleaner->perform();

        $this->assertTrue(models\Account::exists($account->id));
    }

    public function testPerformDoesNotMovePaymentsAndPotUsagesOfSyncAccounts(): void
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

        $cleaner = new Cleaner();
        $cleaner->perform();

        /** @var models\Payment */
        $payment = $payment->reload();
        /** @var models\PotUsage */
        $pot_usage = $pot_usage->reload();
        $this->assertSame($account->id, $payment->account_id);
        $this->assertSame($account->id, $pot_usage->account_id);
    }
}
