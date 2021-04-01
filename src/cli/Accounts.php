<?php

namespace Website\cli;

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
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function index($request)
    {
        $accounts = models\Account::listAll();
        $formatted_accounts = array_map(function ($account) {
            return "{$account->id} {$account->email}";
        }, $accounts);

        return \Minz\Response::Text(200, implode("\n", $formatted_accounts));
    }

    /**
     * @request_param string email
     *
     * @response 400
     *     if the email is invalid
     * @response 200
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function create($request)
    {
        $email = $request->param('email', '');
        $account = models\Account::init($email);
        $errors = $account->validate();
        if ($errors) {
            return \Minz\Response::Text(400, implode(' ', $errors));
        }

        $account->save();

        return \Minz\Response::Text(200, "Account {$account->id} ({$account->email}) created.");
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
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function loginUrl($request)
    {
        $account_id = $request->param('account_id');
        $service = $request->param('service');

        $account = models\Account::find($account_id);
        if (!$account) {
            return \Minz\Response::Text(404, 'This account doesn’t exist.');
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

        return \Minz\Response::Text(200, $login_url);
    }

    /**
     * @response 200
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
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
}
