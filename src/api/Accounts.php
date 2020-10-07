<?php

namespace Website\api;

use Website\models;
use Website\utils;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Accounts
{
    /**
     * @request_header string PHP_AUTH_USER
     * @request_param string email
     * @request_param datetime expired_at (TODO temporary, to allow a migration)
     *
     * @response 401
     *     if the auth header is invalid
     * @response 400
     *     if the account doesn’t exist and email is invalid
     * @response 200
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function show($request)
    {
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!hash_equals($private_key, $auth_token)) {
            return \Minz\Response::unauthorized();
        }

        $email = utils\Email::sanitize($request->param('email', ''));
        $expired_at = $request->param('expired_at');
        $account_dao = new models\dao\Account();
        $db_account = $account_dao->findBy([
            'email' => $email,
        ]);

        if ($db_account) {
            $account = new models\Account($db_account);
        } else {
            $account = models\Account::init($email);
            if ($expired_at) {
                $account->setExpiredAt($expired_at);
            }

            $errors = $account->validate();
            if ($errors) {
                $output = new \Minz\Output\Text(implode(' ', $errors));
                return new \Minz\Response(400, $output);
            }

            $account_dao->save($account);
        }

        $json_output = json_encode([
            'id' => $account->id,
        ]);

        $output = new \Minz\Output\Text($json_output);
        $response = new \Minz\Response(200, $output);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @request_header string PHP_AUTH_USER
     * @request_param string account_id
     *
     * @response 401
     *     if the auth header is invalid
     * @response 404
     *     if the account_id doesn't exist
     * @response 200
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function loginUrl($request)
    {
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!hash_equals($private_key, $auth_token)) {
            return \Minz\Response::unauthorized();
        }

        $account_id = $request->param('account_id');
        $account_dao = new models\dao\Account();
        $token_dao = new models\dao\Token();

        $db_account = $account_dao->find($account_id);
        if (!$db_account) {
            return \Minz\Response::notFound();
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
        $json_output = json_encode([
            'url' => $login_url,
        ]);

        $output = new \Minz\Output\Text($json_output);
        $response = new \Minz\Response(200, $output);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @request_header string PHP_AUTH_USER
     * @request_param string account_id
     *
     * @response 401
     *     if the auth header is invalid
     * @response 404
     *     if the account_id doesn't exist
     * @response 200
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function expiredAt($request)
    {
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!hash_equals($private_key, $auth_token)) {
            return \Minz\Response::unauthorized();
        }

        $account_id = $request->param('account_id');
        $account_dao = new models\dao\Account();

        $db_account = $account_dao->find($account_id);
        if (!$db_account) {
            return \Minz\Response::notFound();
        }

        $account = new models\Account($db_account);
        if ($account->expired_at->getTimestamp() > 0) {
            $expired_at = $account->expired_at->format(\Minz\Model::DATETIME_FORMAT);
        } else {
            $expired_at = 'no expiration';
        }
        $json_output = json_encode([
            'expired_at' => $expired_at,
        ]);

        $output = new \Minz\Output\Text($json_output);
        $response = new \Minz\Response(200, $output);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }
}
