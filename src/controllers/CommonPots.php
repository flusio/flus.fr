<?php

namespace Website\controllers;

use Website\models;
use Website\services;
use Website\utils;

class CommonPots
{
    /**
     * Show the page about the common pot.
     *
     * @response 200
     */
    public function show()
    {
        $common_pot_amount = models\PotUsage::findAvailableAmount() / 100;
        return \Minz\Response::ok('common_pots/show.phtml', [
            'common_pot_amount' => number_format($common_pot_amount, 2, ',', '&nbsp;'),
        ]);
    }

    /**
     * Show the page allowing to contribute to the common pot
     *
     * @response 401
     *     if the user is not connected
     * @response 302 /account/address
     *     if the address is not set
     * @response 200
     *     on success
     */
    public function contribution()
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if ($account->mustSetAddress()) {
            return \Minz\Response::redirect('account address');
        }

        return \Minz\Response::ok('common_pots/contribution.phtml', [
            'account' => $account,
            'ongoing_payment' => $account->ongoingPayment(),
            'amount' => 30,
        ]);
    }

    /**
     * Handle the payment request for the common pot contribution and redirect to Stripe.
     *
     * @request_param string csrf
     * @request_param integer amount must be between 1 and 1000
     * @request_param boolean accept_cgv
     *
     * @response 401
     *     if the user is not connected
     * @response 302 /account/address
     *     if the address is not set
     * @response 400
     *     if accept_cgv is false or if CSRF or amount are invalid
     * @response 302 /payments/:id/pay
     *     on success
     */
    public function contribute($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if ($account->mustSetAddress()) {
            return \Minz\Response::redirect('account address');
        }

        $accept_cgv = $request->paramBoolean('accept_cgv', false);
        $amount = $request->paramInteger('amount', 0);

        if (!$accept_cgv) {
            return \Minz\Response::badRequest('common_pots/contribution.phtml', [
                'account' => $account,
                'ongoing_payment' => $account->ongoingPayment(),
                'amount' => $amount,
                'errors' => [
                    'cgv' => 'Vous devez accepter ces conditions pour participer à la cagnotte.',
                ],
            ]);
        }

        $payment = models\Payment::initCommonPotFromAccount($account, $amount);
        $errors = $payment->validate();
        if ($errors) {
            return \Minz\Response::badRequest('common_pots/contribution.phtml', [
                'account' => $account,
                'ongoing_payment' => $account->ongoingPayment(),
                'amount' => $amount,
                'errors' => $errors,
            ]);
        }

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return \Minz\Response::badRequest('common_pots/contribution.phtml', [
                'account' => $account,
                'ongoing_payment' => $account->ongoingPayment(),
                'amount' => $amount,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $stripe_service = new services\Stripe();
        $stripe_session = $stripe_service->createSession(
            $payment,
            'Participation à la cagnotte de Flus',
            \Minz\Url::absoluteFor('Payments#succeeded'),
            \Minz\Url::absoluteFor('Payments#canceled')
        );

        $payment->payment_intent_id = $stripe_session->payment_intent;
        $payment->session_id = $stripe_session->id;
        $payment->save();

        return \Minz\Response::redirect('Payments#pay', [
            'id' => $payment->id,
        ]);
    }

    /**
     * Show the page allowing to use the common pot
     *
     * @response 401
     *     if the user is not connected
     * @response 302 /account/address
     *     if the address is not set
     * @response 200
     *     on success
     */
    public function usage()
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if ($account->mustSetAddress()) {
            return \Minz\Response::redirect('account address');
        }

        $common_pot_amount = models\PotUsage::findAvailableAmount() / 100;
        return \Minz\Response::ok('common_pots/usage.phtml', [
            'account' => $account,
            'common_pot_amount' => number_format($common_pot_amount, 2, ',', '&nbsp;'),
            'full_enough' => $common_pot_amount >= 3,
            'free_account' => $account->isFree(),
            'expire_soon' => $account->expired_at <= \Minz\Time::fromNow(7, 'days'),
            'reminder' => $account->reminder,
        ]);
    }

    /**
     * Handle the request to use the common pot.
     *
     * @request_param string csrf
     * @request_param boolean accept_cgv
     * @request_param boolean reminder
     *
     * @response 401
     *     if the user is not connected
     * @response 302 /account/address
     *     if the address is not set
     * @response 400
     *     if accept_cgv is false, CSRF is invalid, common pot is not full
     *     enough or if the account doesn't expire soon enough
     * @response 302 /account
     *     on success
     */
    public function use($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if ($account->mustSetAddress()) {
            return \Minz\Response::redirect('account address');
        }

        $common_pot_amount = models\PotUsage::findAvailableAmount() / 100;
        $full_enough = $common_pot_amount >= 3;
        $common_pot_amount = number_format($common_pot_amount, 2, ',', '&nbsp;');
        $free_account = $account->isFree();
        $expire_soon = $account->expired_at <= \Minz\Time::fromNow(7, 'days');

        $accept_cgv = $request->paramBoolean('accept_cgv', false);
        $reminder = $request->paramBoolean('reminder', false);

        if (!$accept_cgv) {
            return \Minz\Response::badRequest('common_pots/usage.phtml', [
                'account' => $account,
                'common_pot_amount' => $common_pot_amount,
                'full_enough' => $full_enough,
                'free_account' => $free_account,
                'expire_soon' => $expire_soon,
                'reminder' => $reminder,
                'errors' => [
                    'cgv' => 'Vous devez accepter ces conditions pour bénéficier de la cagnotte.',
                ],
            ]);
        }

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return \Minz\Response::badRequest('common_pots/usage.phtml', [
                'account' => $account,
                'common_pot_amount' => $common_pot_amount,
                'full_enough' => $full_enough,
                'free_account' => $free_account,
                'expire_soon' => $expire_soon,
                'reminder' => $reminder,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        if (!$full_enough) {
            return \Minz\Response::badRequest('common_pots/usage.phtml', [
                'account' => $account,
                'common_pot_amount' => $common_pot_amount,
                'full_enough' => $full_enough,
                'free_account' => $free_account,
                'expire_soon' => $expire_soon,
                'reminder' => $reminder,
                'error' => 'La cagnotte n’est pas suffisamment fournie pour pouvoir en bénéficier.',
            ]);
        }

        if ($free_account || !$expire_soon) {
            return \Minz\Response::badRequest('common_pots/usage.phtml', [
                'account' => $account,
                'common_pot_amount' => $common_pot_amount,
                'full_enough' => $full_enough,
                'free_account' => $free_account,
                'expire_soon' => $expire_soon,
                'reminder' => $reminder,
                'error' => 'Votre abonnement n’est pas encore prêt d’expirer, veuillez attendre un peu.',
            ]);
        }

        $pot_usage = new models\PotUsage($account, 'month');
        $pot_usage->save();

        $account->extendSubscription($pot_usage->frequency);
        $account->reminder = $reminder;
        $account->save();

        return \Minz\Response::redirect('account');
    }
}
