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
        $account_dao = new models\dao\Account();
        $db_accounts = $account_dao->listAll();
        $accounts = array_map(function ($db_account) {
            return "{$db_account['id']} {$db_account['email']}";
        }, $db_accounts);

        return \Minz\Response::Text(200, implode("\n", $accounts));
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

        $account_dao = new models\dao\Account();
        $account_id = $account_dao->save($account);

        return \Minz\Response::Text(200, "Account {$account_id} ({$account->email}) created.");
    }

    /**
     * @request_param string account_id
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
        $account_dao = new models\dao\Account();
        $token_dao = new models\dao\Token();

        $db_account = $account_dao->find($account_id);
        if (!$db_account) {
            return \Minz\Response::Text(404, 'This account doesn’t exist.');
        }

        $account = new models\Account($db_account);
        $token = models\Token::init(10, 'minutes');
        $account->access_token = $token->token;
        $token_dao->save($token);
        $account_dao->save($account);

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
        $account_dao = new models\dao\Account();
        $token_dao = new models\dao\Token();
        $mailer = new mailers\Accounts();

        $db_accounts = $account_dao->listBy([
            'reminder' => true,
        ]);
        $number_reminders = 0;

        foreach ($db_accounts as $db_account) {
            $account = new models\Account($db_account);
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
                $account->access_token = $token->token;
                $token_dao->save($token);
                $account_dao->save($account);

                // Then, send the email
                $mailer->sendReminderSubscriptionEnded($account);
                $number_reminders += 1;
            } elseif ($interval->invert === 0 && ($diff_days === 2 || $diff_days === 7)) {
                // subscription end in 2 or 7 days

                // First create a login token
                $token = models\Token::init(24, 'hours');
                $account->access_token = $token->token;
                $token_dao->save($token);
                $account_dao->save($account);

                // Then, send the email
                $mailer->sendReminderSubscriptionEnding($account);
                $number_reminders += 1;
            }
        }

        return \Minz\Response::text(200, "{$number_reminders} reminders sent");
    }
}
