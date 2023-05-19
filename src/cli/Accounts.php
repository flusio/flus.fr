<?php

namespace Website\cli;

use Minz\Request;
use Minz\Response;
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
    public function index(Request $request): Response
    {
        $accounts = models\Account::listAll();
        $formatted_accounts = array_map(function ($account) {
            return "{$account->id} {$account->email}";
        }, $accounts);

        return Response::text(200, implode("\n", $formatted_accounts));
    }

    /**
     * @request_param string email
     *
     * @response 400
     *     if the email is invalid
     * @response 200
     *     on success
     */
    public function create(Request $request): Response
    {
        $email = $request->param('email', '');
        $account = new models\Account($email);

        /** @var array<string, string> */
        $errors = $account->validate();
        if ($errors) {
            return Response::text(400, implode(' ', $errors));
        }

        $account->save();

        return Response::text(200, "Account {$account->id} ({$account->email}) created.");
    }

    /**
     * @request_param string id
     *
     * @response 404
     *     if the account doesn't exist
     * @response 200
     *     on success
     */
    public function loginUrl(Request $request): Response
    {
        $id = $request->param('id');

        $account = models\Account::find($id);
        if (!$account) {
            return Response::text(404, 'This account doesn’t exist.');
        }

        $token = new models\Token(10, 'minutes');
        $token->save();

        $account->access_token = $token->token;
        $account->save();

        $login_url = \Minz\Url::absoluteFor('account login', [
            'account_id' => $account->id,
            'access_token' => $account->access_token,
        ]);

        return Response::text(200, $login_url);
    }
}
