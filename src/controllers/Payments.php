<?php

namespace Website\controllers;

use Website\models;
use Website\services;
use Website\utils;

class Payments
{
    /**
     * Handle the payment itself
     *
     * Parameter is:
     *
     * - `id` of the Payment
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function pay($request)
    {
        $payment_id = $request->param('id');
        $payment = models\Payment::find($payment_id);

        if (!$payment) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        if ($payment->completed_at) {
            return \Minz\Response::badRequest();
        }

        $response = \Minz\Response::ok('stripe/redirection.phtml', [
            'stripe_public_key' => \Minz\Configuration::$application['stripe_public_key'],
            'stripe_session_id' => $payment->session_id,
        ]);
        $response->setContentSecurityPolicy('default-src', "'self' js.stripe.com");
        $response->setContentSecurityPolicy('script-src', "'self' 'unsafe-inline' js.stripe.com");
        return $response;
    }

    /**
     * Handle the successful redirection from Stripe.
     *
     * @return \Minz\Response
     */
    public function succeeded()
    {
        $response = \Minz\Response::ok('payments/succeeded.phtml');
        $response->removeCookie('__stripe_mid', [
            'secure' => false,
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
        $response->removeCookie('__stripe_sid', [
            'secure' => false,
            'httponly' => false,
            'samesite' => 'Lax',
        ]);

        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return $response;
        }

        $account = models\Account::find($user['account_id']);
        $ongoing_payment = $account->ongoingPayment();
        if (!$ongoing_payment || $ongoing_payment->is_paid || !$ongoing_payment->session_id) {
            return $response;
        }

        $stripe_service = new services\Stripe();
        $session = $stripe_service->retrieveSession($ongoing_payment->session_id);
        if ($session->payment_intent->status === 'succeeded') {
            $ongoing_payment->is_paid = true;
            $ongoing_payment->save();
        }

        return $response;
    }

    /**
     * Handle the cancelation redirection from Stripe.
     *
     * @return \Minz\Response
     */
    public function canceled()
    {
        $response = \Minz\Response::ok('payments/canceled.phtml');
        $response->removeCookie('__stripe_mid', [
            'secure' => false,
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
        $response->removeCookie('__stripe_sid', [
            'secure' => false,
            'httponly' => false,
            'samesite' => 'Lax',
        ]);

        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return $response;
        }

        $account = models\Account::find($user['account_id']);
        $ongoing_payment = $account->ongoingPayment();
        if (!$ongoing_payment || $ongoing_payment->is_paid || !$ongoing_payment->session_id) {
            return $response;
        }

        $stripe_service = new services\Stripe();
        $session = $stripe_service->retrieveSession($ongoing_payment->session_id);
        $payment_intent = $session->payment_intent;
        // see statuses lifecycle at https://stripe.com/docs/payments/intents#intent-statuses
        if ($payment_intent->status !== 'processing' && $payment_intent->status !== 'succeeded') {
            try {
                $payment_intent->cancel();
            } catch (\Stripe\Exception\ApiErrorException $e) {
                // do nothing on purpose: the payment was already canceled
                // by Stripe on their side.
            }

            models\Payment::delete($ongoing_payment->id);
        }

        return $response;
    }
}
