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
class Subscriptions
{
    /**
     * @response 401
     *     if the user is not connected, or if the account is managed
     * @response 302 /account/address
     *     if the address is not set
     * @response 200
     *     on success
     */
    public function init(Request $request): Response
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

        if ($account->isManaged()) {
            return Response::unauthorized('accounts/blocked.phtml');
        }

        return Response::ok('subscriptions/init.phtml', [
            'contribution_price' => models\Payment::contributionPrice(),
            'account' => $account,
            'amount' => $account->preferredAmount(),
            'ongoing_payment' => $account->ongoingPayment(),
        ]);
    }

    /**
     * Initialize a subscription Payment for the current account and call
     * Stripe API to start a payment session.
     *
     * @request_param string csrf
     * @request_param integer amount must be between 0 and 120
     *
     * @response 401
     *     if the user is not connected, or if the account is managed
     * @response 302 /account/address
     *     if the address is not set
     * @response 400
     *     if CSRF is invalid, or the subscription is not yet to renew (i.e.
     *     expires in more than 1 month)
     * @response 302 /payments/:id/pay
     *     on success
     */
    public function renew(Request $request): Response
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return Response::unauthorized('unauthorized.phtml');
        }

        $account = models\Account::find($user['account_id']);
        if (!$account) {
            return Response::unauthorized('unauthorized.phtml');
        }

        if ($account->isManaged()) {
            return Response::unauthorized('accounts/blocked.phtml');
        }

        /** @var int */
        $amount = $request->paramInteger('amount', 0);

        /** @var string */
        $tariff = $request->param('tariff', '');

        $right_of_withdrawal = $request->paramBoolean('right_of_withdrawal');

        if (!\Minz\Csrf::validate($request->param('csrf', ''))) {
            return Response::badRequest('subscriptions/init.phtml', [
                'contribution_price' => models\Payment::contributionPrice(),
                'account' => $account,
                'amount' => $amount,
                'ongoing_payment' => $account->ongoingPayment(),
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        if ($account->expired_at > \Minz\Time::fromNow(1, 'month')) {
            return Response::badRequest('subscriptions/init.phtml', [
                'contribution_price' => models\Payment::contributionPrice(),
                'account' => $account,
                'amount' => $amount,
                'ongoing_payment' => $account->ongoingPayment(),
                'error' => 'Vous pourrez renouveler à 1 mois de l’expiration de votre abonnement.',
            ]);
        }

        if ($account->mustSetAddress()) {
            return Response::redirect('account profile');
        }

        if (!$right_of_withdrawal) {
            return Response::badRequest('subscriptions/init.phtml', [
                'contribution_price' => models\Payment::contributionPrice(),
                'account' => $account,
                'amount' => $amount,
                'ongoing_payment' => $account->ongoingPayment(),
                'errors' => [
                    'right_of_withdrawal' => 'Vous devez cocher cette case pour continuer',
                ],
            ]);
        }

        if (in_array($tariff, ['solidarity', 'stability', 'contribution'])) {
            $preferred_tariff = $tariff;
        } else {
            $preferred_tariff = strval($amount);
        }

        $account->preferred_tariff = $preferred_tariff;
        $account->save();

        if ($amount === 0) {
            $account->extendSubscription();
            $account->save();

            $managed_accounts = $account->managedAccounts();
            foreach ($managed_accounts as $managed_account) {
                $managed_account->extendSubscription();
                $managed_account->save();
            }

            $quantity_renewals = count($managed_accounts) + 1;
            $free_renewal = new models\FreeRenewal($quantity_renewals);
            $free_renewal->save();

            return Response::redirect('Payments#succeeded');
        }

        $payment = models\Payment::initSubscriptionFromAccount($account, $amount);

        $errors = $payment->validate();
        if ($errors) {
            return Response::badRequest('subscriptions/init.phtml', [
                'contribution_price' => models\Payment::contributionPrice(),
                'account' => $account,
                'amount' => $amount,
                'ongoing_payment' => $account->ongoingPayment(),
                'errors' => $errors,
            ]);
        }

        $stripe_service = new services\Stripe();
        $period = '1 an';
        $stripe_session = $stripe_service->createSession(
            $payment,
            "Abonnement à Flus ({$period})",
            $request->param('success_url', \Minz\Url::absoluteFor('Payments#succeeded')),
            $request->param('cancel_url', \Minz\Url::absoluteFor('Payments#canceled'))
        );

        if (!$stripe_session) {
            return Response::internalServerError('internal_server_error.phtml', [
                'error' => 'La session Stripe n’a pas pu être initialisée',
            ]);
        }

        $payment->payment_intent_id = $stripe_session->payment_intent;
        $payment->session_id = $stripe_session->id;
        $payment->save();

        return Response::redirect('Payments#pay', [
            'id' => $payment->id,
        ]);
    }
}
