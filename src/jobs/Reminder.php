<?php

namespace Website\jobs;

use Minz\Job;
use Website\mailers;
use Website\models;

class Reminder extends Job
{
    public static function install(): void
    {
        $job = new self();
        if (!self::existsBy(['name' => $job->name])) {
            $perform_at = \Minz\Time::relative('tomorrow 6:42');
            $job->performLater($perform_at);
        }
    }

    public function __construct()
    {
        parent::__construct();
        $this->frequency = '+1 day';
    }

    /**
     * Send reminder emails.
     */
    public function perform(): void
    {
        $mailer = new mailers\Accounts();

        $accounts = models\Account::listBy([
            'reminder' => true,
        ]);
        $number_reminders = 0;

        foreach ($accounts as $account) {
            if ($account->isFree() || !$account->isSync()) {
                continue;
            }

            $today = \Minz\Time::now();
            $interval = $today->diff($account->expired_at);
            $diff_days = $interval->days;

            if ($interval->invert === 1 && $diff_days === 1) {
                // subscription ended yesterday

                // First create a login token
                $token = new models\Token(24, 'hours');
                $token->save();

                $account->access_token = $token->token;
                $account->save();

                // Then, send the email
                try {
                    $mailer->sendReminderSubscriptionEnded($account);
                    $number_reminders += 1;
                } catch (\Minz\Errors\MailerError $e) {
                    \Minz\Log::error("Failed to send reminder email to {$account->email}");
                }
            } elseif ($interval->invert === 0 && ($diff_days === 2 || $diff_days === 7)) {
                // subscription end in 2 or 7 days

                // First create a login token
                $token = new models\Token(24, 'hours');
                $token->save();

                $account->access_token = $token->token;
                $account->save();

                // Then, send the email
                try {
                    $mailer->sendReminderSubscriptionEnding($account);
                    $number_reminders += 1;
                } catch (\Minz\Errors\MailerError $e) {
                    \Minz\Log::error("Failed to send reminder email to {$account->email}");
                }
            }
        }

        \Minz\Log::notice("{$number_reminders} reminders sent.");
    }
}
