<?php

namespace Website\controllers;

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
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function show($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if ($account->mustSetAddress()) {
            return \Minz\Response::redirect('account address');
        }

        $ongoing_payment = $account->ongoingPayment();
        if ($ongoing_payment && $ongoing_payment->is_paid) {
            // If the ongoing payment is paid, we can complete it so it’s no
            // longer ongoing :)
            $payment_completer = new services\PaymentCompleter();
            $payment_completer->complete($ongoing_payment);
            $ongoing_payment = null;
        }

        return \Minz\Response::ok('accounts/show.phtml', [
            'account' => $account,
            'payments' => $account->payments(),
            'ongoing_payment' => $ongoing_payment,
        ]);
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
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function login($request)
    {
        $account_id = $request->param('account_id');
        $access_token = $request->param('access_token');

        $account = models\Account::find($account_id);
        if (!$account) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        if (!$account->checkAccess($access_token)) {
            return \Minz\Response::badRequest('bad_request.phtml');
        }

        utils\CurrentUser::logUserIn($account->id);

        models\Token::delete($account->access_token);

        return \Minz\Response::redirect('account');
    }

    /**
     * @response 302
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function logout($request)
    {
        $user = utils\CurrentUser::get();

        $csrf = new \Minz\CSRF();
        if ($csrf->validateToken($request->param('csrf')) && $user) {
            $account = models\Account::find($user['account_id']);
            utils\CurrentUser::logOut();
            if ($account->preferred_service === 'flusio') {
                return \Minz\Response::found('https://app.flus.fr');
            } else {
                return \Minz\Response::found('https://flus.io');
            }
        }

        return \Minz\Response::redirect('home');
    }

    /**
     * @response 401
     *     if the user is not connected
     * @response 200
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function address($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        return \Minz\Response::ok('accounts/address.phtml', [
            'account' => $account,
            'email' => $account->email,
            'address' => $account->address(),
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
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function updateAddress($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);

        $email = $request->param('email');
        $address = $request->param('address', $account->address());
        $account->email = $email;
        $account->setAddress($address);

        $errors = $account->validate();
        if (!$account->address_first_name) {
            $errors['address_first_name'] = 'Votre prénom est obligatoire.';
        }
        if (!$account->address_last_name) {
            $errors['address_last_name'] = 'Votre nom est obligatoire.';
        }

        if ($account->address_address1 || $account->address_postcode || $account->address_city) {
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
            return \Minz\Response::badRequest('accounts/address.phtml', [
                'account' => $account,
                'email' => $email,
                'address' => $address,
                'countries' => utils\Countries::listSorted(),
                'errors' => $errors,
            ]);
        }

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
            return \Minz\Response::badRequest('accounts/address.phtml', [
                'account' => $account,
                'email' => $email,
                'address' => $address,
                'countries' => utils\Countries::listSorted(),
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $account->save();

        return \Minz\Response::redirect('account');
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
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function setReminder($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);

        $reminder = $request->param('reminder', false);
        $from = $request->param('from', 'account');

        $csrf = new \Minz\CSRF();
        if ($csrf->validateToken($request->param('csrf'))) {
            $account->reminder = filter_var($reminder, FILTER_VALIDATE_BOOLEAN);
            $account->save();
        }

        return \Minz\Response::redirect($from);
    }
}
