<?php

namespace Website\controllers;

use Website\models;
use Website\services;
use Website\utils;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Subscriptions
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
    public function init($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if ($account->mustSetAddress()) {
            return \Minz\Response::redirect('account address');
        }

        return \Minz\Response::ok('subscriptions/init.phtml', [
            'account' => $account,
            'reminder' => $account->reminder,
        ]);
    }

    /**
     * Initialize a subscription Payment for the current account and call
     * Stripe API to start a payment session.
     *
     * @request_param string csrf
     * @request_param string frequency (month or year)
     * @request_param boolean reminder
     *
     * @response 401
     *     if the user is not connected
     * @response 302 /account/address
     *     if the address is not set
     * @response 400
     *     if CSRF or frequency are invalid
     * @response 302 /payments/:id/pay
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function renew($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if ($account->mustSetAddress()) {
            return \Minz\Response::redirect('account address');
        }

        $frequency = $request->param('frequency');
        $reminder = $request->param('reminder', false);
        $reminder = filter_var($reminder, FILTER_VALIDATE_BOOLEAN);

        $payment = models\Payment::initSubscriptionFromAccount($account, $frequency);
        $errors = $payment->validate();
        if ($errors) {
            return \Minz\Response::badRequest('subscriptions/init.phtml', [
                'account' => $account,
                'reminder' => $reminder,
                'errors' => $errors,
            ]);
        }

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
            return \Minz\Response::badRequest('subscriptions/init.phtml', [
                'account' => $account,
                'reminder' => $reminder,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $stripe_service = new services\Stripe(
            $request->param('success_url', \Minz\Url::absoluteFor('Payments#succeeded')),
            $request->param('cancel_url', \Minz\Url::absoluteFor('Payments#canceled'))
        );

        $period = $payment->frequency === 'month' ? '1 mois' : '1 an';
        $stripe_session = $stripe_service->createSession(
            $payment,
            "Abonnement à Flus ({$period})"
        );

        $payment->payment_intent_id = $stripe_session->payment_intent;
        $payment->session_id = $stripe_session->id;
        $payment->save();

        $account->preferred_frequency = $payment->frequency;
        $account->reminder = $reminder;
        $account->save();

        return \Minz\Response::redirect('Payments#pay', [
            'id' => $payment->id,
        ]);
    }
}
