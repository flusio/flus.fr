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
            return Response::redirect('account address');
        }

        $ongoing_payment = $account->ongoingPayment();
        if ($ongoing_payment && $ongoing_payment->is_paid) {
            // If the ongoing payment is paid, we can complete it so it’s no
            // longer ongoing :)
            $payment_completer = new services\PaymentCompleter();
            $payment_completer->complete($ongoing_payment);
            $ongoing_payment = null;
        }

        return Response::ok('accounts/show.phtml', [
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
    public function address(Request $request): Response
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        return Response::ok('accounts/address.phtml', [
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
     */
    public function updateAddress(Request $request): Response
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
        $address = $request->paramArray('address', $account->address());
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
            return Response::badRequest('accounts/address.phtml', [
                'account' => $account,
                'email' => $email,
                'address' => $address,
                'countries' => utils\Countries::listSorted(),
                'errors' => $errors,
            ]);
        }

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return Response::badRequest('accounts/address.phtml', [
                'account' => $account,
                'email' => $email,
                'address' => $address,
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
}
