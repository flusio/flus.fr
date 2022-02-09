<?php

namespace Website\controllers\cli;

use Website\models;
use Website\mailers;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Accounts
{
    /**
     * @response 200
     */
    public function index($request)
    {
        $accounts = models\Account::listAll();
        $formatted_accounts = array_map(function ($account) {
            return "{$account->id} {$account->email}";
        }, $accounts);

        return \Minz\Response::text(200, implode("\n", $formatted_accounts));
    }

    /**
     * @request_param string email
     *
     * @response 400
     *     if the email is invalid
     * @response 200
     *     on success
     */
    public function create($request)
    {
        $email = $request->param('email', '');
        $account = models\Account::init($email);
        $errors = $account->validate();
        if ($errors) {
            return \Minz\Response::text(400, implode(' ', $errors));
        }

        $account->save();

        return \Minz\Response::text(200, "Account {$account->id} ({$account->email}) created.");
    }

    /**
     * @request_param string account_id
     * @request_param string service
     *     The name of the service making the request ('flusio' or 'freshrss').
     *     If the variable is invalid, it defaults to 'flusio'.
     *
     * @response 404
     *     if the account doesn't exist
     * @response 200
     *     on success
     */
    public function loginUrl($request)
    {
        $account_id = $request->param('account_id');
        $service = $request->param('service');

        $account = models\Account::find($account_id);
        if (!$account) {
            return \Minz\Response::text(404, 'This account doesn’t exist.');
        }

        if ($service !== 'flusio' && $service !== 'freshrss') {
            $service = 'flusio';
        }

        $token = models\Token::init(10, 'minutes');
        $token->save();

        $account->access_token = $token->token;
        $account->preferred_service = $service;
        $account->save();

        $login_url = \Minz\Url::absoluteFor('account login', [
            'account_id' => $account->id,
            'access_token' => $account->access_token,
        ]);

        return \Minz\Response::text(200, $login_url);
    }

    /**
     * @response 200
     */
    public function remind($request)
    {
        $mailer = new mailers\Accounts();

        $accounts = models\Account::listBy([
            'reminder' => true,
        ]);
        $number_reminders = 0;

        foreach ($accounts as $account) {
            if ($account->isFree()) {
                continue;
            }

            $today = \Minz\Time::now();
            $interval = $today->diff($account->expired_at);
            $diff_days = $interval->days;

            if ($interval->invert === 1 && $diff_days === 1) {
                // subscription ended yesterday

                // First create a login token
                $token = models\Token::init(24, 'hours');
                $token->save();

                $account->access_token = $token->token;
                $account->save();

                // Then, send the email
                $mailer->sendReminderSubscriptionEnded($account);
                $number_reminders += 1;
            } elseif ($interval->invert === 0 && ($diff_days === 2 || $diff_days === 7)) {
                // subscription end in 2 or 7 days

                // First create a login token
                $token = models\Token::init(24, 'hours');
                $token->save();

                $account->access_token = $token->token;
                $account->save();

                // Then, send the email
                $mailer->sendReminderSubscriptionEnding($account);
                $number_reminders += 1;
            }
        }

        if ($number_reminders > 0) {
            return \Minz\Response::text(200, "{$number_reminders} reminders sent");
        } else {
            return \Minz\Response::text(200, '');
        }
    }

    /**
     * Clear non-synced accounts.
     *
     * An account is considered as non-synced if its last_sync_at is older than
     * 2 days.
     *
     * Payments and pot usages attached to deleted accounts are rattached to
     * the default account.
     *
     * @response 200
     */
    public function clear($request)
    {
        $date = \Minz\Time::ago(2, 'days');

        $accounts_to_delete = models\Account::daoToList('listByLastSyncAtOlderThan', $date);
        $accounts_ids = array_column($accounts_to_delete, 'id');

        $payments = models\Payment::listBy([
            'account_id' => $accounts_ids,
        ]);
        $payments_ids = array_column($payments, 'id');

        $pot_usages = models\PotUsage::listBy([
            'account_id' => $accounts_ids,
        ]);
        $pot_usages_ids = array_column($pot_usages, 'id');

        $default_account = models\Account::defaultAccount();

        models\Payment::daoCall('moveToAccountId', $payments_ids, $default_account->id);
        models\PotUsage::daoCall('moveToAccountId', $pot_usages_ids, $default_account->id);
        models\Account::delete($accounts_ids);

        $number_accounts = count($accounts_ids);
        return \Minz\Response::text(200, "{$number_accounts} accounts have been deleted.");
    }
}
