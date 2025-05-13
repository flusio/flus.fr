<?php

namespace Website\controllers\api;

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
     * @request_header string PHP_AUTH_USER
     * @request_param string email
     *
     * @response 401
     *     if the auth header is invalid
     * @response 400
     *     if the account doesn’t exist and email is invalid
     * @response 200
     *     on success
     */
    public function show(Request $request): Response
    {
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!hash_equals($private_key, $auth_token)) {
            return Response::unauthorized();
        }

        $email = \Minz\Email::sanitize($request->param('email', ''));
        $account = models\Account::findBy([
            'email' => $email,
        ]);

        if (!$account) {
            $account = new models\Account($email);

            $errors = $account->validate();
            if ($errors) {
                return Response::text(400, implode(' ', $errors));
            }

            $account->save();
        } else {
            $account->last_sync_at = \Minz\Time::now();
            $account->save();
        }

        return Response::json(200, [
            'id' => $account->id,
            'expired_at' => $account->expired_at->format(\Minz\Database\Column::DATETIME_FORMAT),
        ]);
    }

    /**
     * @request_header string PHP_AUTH_USER
     * @request_param string account_id
     * @request_param string service
     *     The name of the service making the request ('flus' or 'freshrss').
     *     If the variable is invalid, it defaults to 'flus'.
     *
     * @response 401
     *     if the auth header is invalid
     * @response 404
     *     if the account_id doesn't exist
     * @response 200
     *     on success
     */
    public function loginUrl(Request $request): Response
    {
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!hash_equals($private_key, $auth_token)) {
            return Response::unauthorized();
        }

        $account_id = $request->param('account_id', '');
        $service = strtolower($request->param('service', 'flus'));

        $account = models\Account::find($account_id);
        if (!$account) {
            return Response::notFound();
        }

        if ($service !== 'flus' && $service !== 'freshrss') {
            $service = 'flus';
        }

        $token = new models\Token(10, 'minutes');
        $token->save();

        $account->access_token = $token->token;
        $account->preferred_service = $service;
        $account->save();

        $login_url = \Minz\Url::absoluteFor('account login', [
            'account_id' => $account->id,
            'access_token' => $account->access_token,
        ]);

        return Response::json(200, [
            'url' => $login_url,
        ]);
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
     */
    public function expiredAt(Request $request): Response
    {
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!hash_equals($private_key, $auth_token)) {
            return Response::unauthorized();
        }

        $account_id = $request->param('account_id', '');

        $account = models\Account::find($account_id);
        if (!$account) {
            return Response::notFound();
        }

        $account->last_sync_at = \Minz\Time::now();
        $account->save();

        return Response::json(200, [
            'expired_at' => $account->expired_at->format(\Minz\Database\Column::DATETIME_FORMAT),
        ]);
    }

    /**
     * Return the expiration date of the given accounts and update their
     * last_sync_at properties.
     *
     * @request_header string PHP_AUTH_USER
     * @request_param string account_ids
     *     A JSON array containing the list of account ids to sync
     *
     * @response 401
     *     if the auth header is invalid
     * @response 400
     *     if account_ids is not a valid JSON array
     * @response 200
     *     on success
     */
    public function sync(Request $request): Response
    {
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!hash_equals($private_key, $auth_token)) {
            return Response::unauthorized();
        }

        $account_ids = $request->paramJson('account_ids');
        if (!is_array($account_ids)) {
            return Response::json(400, [
                'error' => 'account_ids is not a valid JSON array',
            ]);
        }

        models\Account::updateLastSyncAt($account_ids, \Minz\Time::now());

        $result = [];
        $accounts = models\Account::listBy(['id' => $account_ids]);
        foreach ($accounts as $account) {
            $result[$account->id] = $account->expired_at->format(\Minz\Database\Column::DATETIME_FORMAT);
        }

        return Response::json(200, $result);
    }
}
