<?php

namespace Website\controllers;

use Minz\Request;
use Minz\Response;
use Website\auth;
use Website\forms;
use Website\models;
use Website\services;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Accounts
{
    /**
     * @response 401
     *     if the user is not connected
     * @response 302 /account/address
     *     if the address is not set
     * @response 302 /account/renew
     *     on success
     */
    public function show(Request $request): Response
    {
        $user = auth\CurrentUser::get();
        if (!$user || auth\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        if ($account->mustSetAddress()) {
            return Response::redirect('account profile');
        }

        return Response::redirect('subscription init');
    }

    /**
     * @request_param string account_id
     * @request_param string access_token
     *
     * @response 404
     *     if the account_id does not exist
     * @response 400
     *     if the access_token is invalid
     * @response 302 /account
     *     on success
     */
    public function login(Request $request): Response
    {
        $account_id = $request->parameters->getString('account_id', '');
        $access_token = $request->parameters->getString('access_token', '');

        $account = models\Account::find($account_id);
        if (!$account) {
            return Response::notFound('not_found.phtml');
        }

        if (!$account->checkAccess($access_token)) {
            return Response::unauthorized('unauthorized.phtml');
        }

        auth\CurrentUser::logUserIn($account->id);

        // Reset the access token immediately.
        if ($account->access_token) {
            models\Token::delete($account->access_token);
        }

        return Response::redirect('account');
    }

    /**
     * @response 302
     */
    public function logout(Request $request): Response
    {
        $user = auth\CurrentUser::get();

        if (\Website\Csrf::validate($request->parameters->getString('csrf', '')) && $user) {
            auth\CurrentUser::logOut();

            $account = models\Account::find($user['account_id']);
            if ($account && $account->preferred_service === 'freshrss') {
                return Response::found('https://rss.flus.fr');
            } else {
                return Response::found('https://app.flus.fr');
            }
        }

        return Response::redirect('home');
    }

    /**
     * @response 401
     *     if the user is not connected, or if account is managed
     * @response 200
     *     on success
     */
    public function profile(Request $request): Response
    {
        $user = auth\CurrentUser::get();
        if (!$user || auth\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        if ($account->isManaged()) {
            return Response::unauthorized('accounts/blocked.phtml');
        }

        $form = new forms\Profile(model: $account);

        return Response::ok('accounts/profile.phtml', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    /**
     * @response 401
     *     if the user is not connected, or if account is managed
     * @response 400
     *     if the email or address is invalid
     * @response 302 /account
     *     on success
     */
    public function updateProfile(Request $request): Response
    {
        $user = auth\CurrentUser::get();
        if (!$user || auth\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        if ($account->isManaged()) {
            return Response::unauthorized('accounts/blocked.phtml');
        }

        $form = new forms\Profile(model: $account);

        $form->handleRequest($request);

        if (!$form->validate()) {
            return Response::badRequest('accounts/profile.phtml', [
                'account' => $account,
                'form' => $form,
            ]);
        }

        $account = $form->model();
        $account->save();

        if ($account->entity_type === 'natural') {
            // Stop managing accounts.
            $managed_accounts = $account->managedAccounts();
            foreach ($managed_accounts as $managed_account) {
                $managed_account->managed_by_id = null;
                $managed_account->save();
            }
        }

        return Response::redirect('account');
    }

    /**
     * @request_param string csrf
     * @request_param boolean reminder
     * @request_param string from A route name (default is "account")
     *
     * @response 401
     *     if the user is not connected, or if account is managed
     * @response 400
     *     if csrf is invalid
     * @response 302 /account
     *     on success
     */
    public function setReminder(Request $request): Response
    {
        $user = auth\CurrentUser::get();
        if (!$user || auth\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        if ($account->isManaged()) {
            return Response::unauthorized('accounts/blocked.phtml');
        }

        $reminder = $request->parameters->getBoolean('reminder', false);
        $from = $request->parameters->getString('from', '');

        if (!$from) {
            $from = 'account';
        }

        if (\Website\Csrf::validate($request->parameters->getString('csrf', ''))) {
            $account->reminder = $reminder;
            $account->save();
        }

        return Response::redirect($from);
    }

    /**
     * @response 401
     *     if the user is not connected, or if account is managed
     * @response 200
     *     on success
     */
    public function invoices(Request $request): Response
    {
        $user = auth\CurrentUser::get();
        if (!$user || auth\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        return Response::ok('accounts/invoices.phtml', [
            'account' => $account,
            'payments' => $account->payments(),
        ]);
    }

    /**
     * @response 401
     *     if the user is not connected, or if account is managed
     * @response 404
     *     if the user's account is not a legal entity
     * @response 200
     *     on success
     */
    public function managed(Request $request): Response
    {
        $user = auth\CurrentUser::get();
        if (!$user || auth\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        if ($account->isManaged()) {
            return Response::unauthorized('accounts/blocked.phtml');
        }

        if ($account->entity_type !== 'legal') {
            return Response::notFound('not_found.phtml');
        }

        return Response::ok('accounts/managed.phtml', [
            'account' => $account,
            'managedAccounts' => $account->managedAccounts(),
            'email' => '',
        ]);
    }

    /**
     * @request_param string csrf
     * @request_param string email
     *
     * @response 401
     *     if the user is not connected, or if account is managed
     * @response 404
     *     if the user's account is not a legal entity
     * @response 400
     *     if csrf, or email is invalid
     * @response 302 /account/managed
     *     on success
     */
    public function addManaged(Request $request): Response
    {
        $user = auth\CurrentUser::get();
        if (!$user || auth\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        if ($account->isManaged()) {
            return Response::unauthorized('accounts/blocked.phtml');
        }

        if ($account->entity_type !== 'legal') {
            return Response::notFound('not_found.phtml');
        }

        $email = $request->parameters->getString('email', '');
        $csrf = $request->parameters->getString('csrf', '');

        if (!\Website\Csrf::validate($csrf)) {
            return Response::badRequest('accounts/managed.phtml', [
                'account' => $account,
                'managedAccounts' => $account->managedAccounts(),
                'email' => $email,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $email = \Minz\Email::sanitize($email);

        if (!\Minz\Email::validate($email)) {
            return Response::badRequest('accounts/managed.phtml', [
                'account' => $account,
                'managedAccounts' => $account->managedAccounts(),
                'email' => $email,
                'errors' => [
                    'email' => 'Veuillez saisir une adresse email valide.',
                ],
            ]);
        }

        $managed_account = models\Account::findBy([
            'email' => $email,
        ]);

        if (!$managed_account) {
            $managed_account = new models\Account($email);
        }

        $default_account = models\Account::defaultAccount();

        if ($managed_account->id === $default_account->id) {
            return Response::badRequest('accounts/managed.phtml', [
                'account' => $account,
                'managedAccounts' => $account->managedAccounts(),
                'email' => $email,
                'errors' => [
                    'email' => 'Vous ne pouvez pas gérer ce compte.',
                ],
            ]);
        }

        if ($managed_account->managed_by_id !== null) {
            return Response::badRequest('accounts/managed.phtml', [
                'account' => $account,
                'managedAccounts' => $account->managedAccounts(),
                'email' => $email,
                'errors' => [
                    'email' => 'Ce compte est déjà géré par un autre compte.',
                ],
            ]);
        }

        $managed_account->managed_by_id = $account->id;

        if ($managed_account->expired_at < $account->expired_at) {
            $managed_account->expired_at = $account->expired_at;
        }

        $managed_account->reminder = false;

        $managed_account->save();

        return Response::redirect('managed accounts');
    }

    /**
     * @request_param string csrf
     * @request_param string id
     *
     * @response 401
     *     if the user is not connected, or if account is managed
     * @response 404
     *     if the id does not exist, or if the user's account is not a legal
     *     entity
     * @response 302 /account/managed
     *     on success
     */
    public function deleteManaged(Request $request): Response
    {
        $user = auth\CurrentUser::get();
        if (!$user || auth\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        if ($account->isManaged()) {
            return Response::unauthorized('accounts/blocked.phtml');
        }

        if ($account->entity_type !== 'legal') {
            return Response::notFound('not_found.phtml');
        }

        $id = $request->parameters->getString('id', '');
        $csrf = $request->parameters->getString('csrf', '');

        if (!\Website\Csrf::validate($csrf)) {
            return Response::redirect('managed accounts');
        }

        $managed_account = models\Account::find($id);

        if (!$managed_account) {
            return Response::notFound('not_found.phtml');
        }

        if ($managed_account->managed_by_id !== $account->id) {
            return Response::redirect('managed accounts');
        }

        $managed_account->managed_by_id = null;
        $managed_account->save();

        return Response::redirect('managed accounts');
    }
}
