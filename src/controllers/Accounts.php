<?php

namespace Website\controllers;

use Minz\Request;
use Minz\Response;
use Website\models;
use Website\services;
use Website\utils;

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
     * @response 200
     *     on success
     */
    public function show(Request $request): Response
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
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
        $account_id = $request->param('account_id');
        $access_token = $request->param('access_token', '');

        $account = models\Account::find($account_id);
        if (!$account) {
            return Response::notFound('not_found.phtml');
        }

        if (!$account->checkAccess($access_token)) {
            return Response::badRequest('bad_request.phtml');
        }

        utils\CurrentUser::logUserIn($account->id);

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
        $user = utils\CurrentUser::get();

        if (\Minz\Csrf::validate($request->param('csrf')) && $user) {
            utils\CurrentUser::logOut();

            $account = models\Account::find($user['account_id']);
            if ($account && $account->preferred_service === 'freshrss') {
                return Response::found('https://flus.io');
            } else {
                return Response::found('https://app.flus.fr');
            }
        }

        return Response::redirect('home');
    }

    /**
     * @response 401
     *     if the user is not connected
     * @response 200
     *     on success
     */
    public function profile(Request $request): Response
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        return Response::ok('accounts/profile.phtml', [
            'account' => $account,
            'email' => $account->email,
            'entity_type' => $account->entity_type,
            'show_address' => false,
            'address' => $account->address(),
            'company_vat_number' => $account->company_vat_number,
            'countries' => utils\Countries::listSorted(),
        ]);
    }

    /**
     * @response 401
     *     if the user is not connected
     * @response 400
     *     if the email or address is invalid
     * @response 302 /account
     *     on success
     */
    public function updateProfile(Request $request): Response
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $email = $request->param('email', '');
        $entity_type = $request->param('entity_type', 'natural');
        $company_vat_number = $request->param('company_vat_number', '');
        $address = $request->paramArray('address', $account->address());
        $show_address = $request->paramBoolean('show_address', false);

        if ($entity_type === 'natural') {
            $company_vat_number = '';
            $address['legal_name'] = '';
        } else {
            $show_address = true;
            $address['first_name'] = '';
            $address['last_name'] = '';
        }

        if (!$show_address) {
            $address['address1'] = '';
            $address['postcode'] = '';
            $address['city'] = '';
        }

        $account->email = $email;
        $account->entity_type = $entity_type;
        $account->setAddress($address);
        $account->company_vat_number = $company_vat_number;

        $errors = $account->validate();

        if ($entity_type === 'natural') {
            if (!$account->address_first_name) {
                $errors['address_first_name'] = 'Votre prénom est obligatoire.';
            }

            if (!$account->address_last_name) {
                $errors['address_last_name'] = 'Votre nom est obligatoire.';
            }
        } elseif (!$account->address_legal_name) {
            $errors['address_legal_name'] = 'Votre raison sociale est obligatoire.';
        }

        if ($show_address) {
            if (!$account->address_address1) {
                $errors['address_address1'] = 'Votre adresse est incomplète.';
            }
            if (!$account->address_postcode) {
                $errors['address_postcode'] = 'Votre adresse est incomplète.';
            }
            if (!$account->address_city) {
                $errors['address_city'] = 'Votre adresse est incomplète.';
            }
        }

        if ($errors) {
            return Response::badRequest('accounts/profile.phtml', [
                'account' => $account,
                'email' => $email,
                'entity_type' => $entity_type,
                'show_address' => $show_address,
                'address' => $address,
                'company_vat_number' => $company_vat_number,
                'countries' => utils\Countries::listSorted(),
                'errors' => $errors,
            ]);
        }

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return Response::badRequest('accounts/profile.phtml', [
                'account' => $account,
                'email' => $email,
                'entity_type' => $entity_type,
                'show_address' => $show_address,
                'address' => $address,
                'company_vat_number' => $company_vat_number,
                'countries' => utils\Countries::listSorted(),
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $account->save();

        return Response::redirect('account');
    }

    /**
     * @request_param string csrf
     * @request_param boolean reminder
     * @request_param string from A route name (default is "account")
     *
     * @response 401
     *     if the user is not connected
     * @response 400
     *     if csrf is invalid
     * @response 302 /account
     *     on success
     */
    public function setReminder(Request $request): Response
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $reminder = $request->paramBoolean('reminder', false);
        $from = $request->param('from', 'account');

        if (\Minz\Csrf::validate($request->param('csrf'))) {
            $account->reminder = $reminder;
            $account->save();
        }

        return Response::redirect($from);
    }

    /**
     * @response 401
     *     if the user is not connected
     * @response 200
     *     on success
     */
    public function invoices(Request $request): Response
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
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
     *     if the user is not connected
     * @response 404
     *     if the user's account is not a legal entity
     * @response 200
     *     on success
     */
    public function managed(Request $request): Response
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
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
     *     if the user is not connected
     * @response 404
     *     if the user's account is not a legal entity
     * @response 400
     *     if csrf, or email is invalid
     * @response 302 /account/managed
     *     on success
     */
    public function addManaged(Request $request): Response
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        if ($account->entity_type !== 'legal') {
            return Response::notFound('not_found.phtml');
        }

        $email = $request->param('email', '');
        $csrf = $request->param('csrf', '');

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
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
}
