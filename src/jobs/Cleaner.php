<?php

namespace Website\jobs;

use Minz\Job;
use Website\models;

class Cleaner extends Job
{
    public static function install(): void
    {
        $job = new self();
        if (!self::existsBy(['name' => $job->name])) {
            $perform_at = \Minz\Time::relative('tomorrow 2:00');
            $job->performLater($perform_at);
        }
    }

    public function __construct()
    {
        parent::__construct();
        $this->frequency = '+1 day';
    }

    /**
     * Clear non-synced accounts.
     *
     * An account is considered as non-synced if its last_sync_at is older than
     * 2 days.
     *
     * Payments and pot usages attached to deleted accounts are rattached to
     * the default account.
     */
    public function perform(): void
    {
        $date = \Minz\Time::ago(2, 'days');

        $accounts_to_delete = models\Account::listToBeDeleted($date);
        $accounts_ids = array_column($accounts_to_delete, 'id');

        $number_accounts = count($accounts_ids);
        \Minz\Log::notice("{$number_accounts} accounts to be deleted.");

        $payments = models\Payment::listBy([
            'account_id' => $accounts_ids,
        ]);
        $payments_ids = array_column($payments, 'id');

        $number_payments = count($payments_ids);
        \Minz\Log::notice("{$number_payments} payments to be moved.");

        $pot_usages = models\PotUsage::listBy([
            'account_id' => $accounts_ids,
        ]);
        $pot_usages_ids = array_column($pot_usages, 'id');

        $number_pot_usages = count($pot_usages_ids);
        \Minz\Log::notice("{$number_pot_usages} pot usages to be moved.");

        $default_account = models\Account::defaultAccount();

        $result = models\Payment::moveToAccountId($payments_ids, $default_account->id);

        if (!$result) {
            throw new \RuntimeException('Moving payments to default account failed.');
        }

        $result = models\PotUsage::moveToAccountId($pot_usages_ids, $default_account->id);

        if (!$result) {
            throw new \RuntimeException('Moving pot usages to default account failed.');
        }

        $result = models\Account::deleteBy(['id' => $accounts_ids]);

        if (!$result) {
            throw new \RuntimeException('Deleting accounts failed.');
        }

        \Minz\Log::notice("{$number_accounts} accounts have been deleted.");
    }
}
