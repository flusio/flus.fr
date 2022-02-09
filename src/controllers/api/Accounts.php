<?php

namespace Website\controllers\api;

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
        $account = models\Account::findBy([
            'email' => $email,
        ]);

        if (!$account) {
            $account = models\Account::init($email);

            $errors = $account->validate();
            if ($errors) {
                return \Minz\Response::text(400, implode(' ', $errors));
            }

            $account->save();
        } else {
            $account->last_sync_at = \Minz\Time::now();
            $account->save();
        }

        return \Minz\Response::json(200, [
            'id' => $account->id,
            'expired_at' => $account->expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
    }

    /**
     * @request_header string PHP_AUTH_USER
     * @request_param string account_id
     * @request_param string service
     *     The name of the service making the request ('flusio' or 'freshrss').
     *     If the variable is invalid, it defaults to 'flusio'.
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
        $service = $request->param('service');

        $account = models\Account::find($account_id);
        if (!$account) {
            return \Minz\Response::notFound();
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

        return \Minz\Response::json(200, [
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

        $account = models\Account::find($account_id);
        if (!$account) {
            return \Minz\Response::notFound();
        }

        $account->last_sync_at = \Minz\Time::now();
        $account->save();

        return \Minz\Response::json(200, [
            'expired_at' => $account->expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
    }

    /**
     * Return the expiration date of the given accounts and update their
     * last_sync_at properties.
     *
     * @request_header string PHP_AUTH_USER
     * @request_param string[] account_ids
     *
     * @response 401
     *     if the auth header is invalid
     * @response 200
     *     on success
     */
    public function sync($request)
    {
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!hash_equals($private_key, $auth_token)) {
            return \Minz\Response::unauthorized();
        }

        $account_ids = $request->paramArray('account_ids', []);

        models\Account::daoCall('updateLastSyncAt', $account_ids, \Minz\Time::now());

        $result = [];
        $accounts = models\Account::listBy(['id' => $account_ids]);
        foreach ($accounts as $account) {
            $result[$account->id] = $account->expired_at->format(\Minz\Model::DATETIME_FORMAT);
        }

        return \Minz\Response::json(200, $result);
    }
}
