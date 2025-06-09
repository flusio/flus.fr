<?php

namespace Website\cli;

use Minz\Request;
use Minz\Response;
use Website\models;

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
        $formatted_accounts = array_map(function (models\Account $account): string {
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
        $email = $request->parameters->getString('email', '');
        $account = new models\Account($email);

        if (!$account->validate()) {
            return Response::text(400, implode(' ', $account->errors()));
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
        $id = $request->parameters->getString('id', '');

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
