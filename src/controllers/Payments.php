<?php

namespace Website\controllers;

use Minz\Request;
use Minz\Response;
use Website\models;
use Website\services;
use Website\utils;

class Payments
{
    /**
     * Handle the payment itself
     *
     * @request_param string id
     *
     * @response 404
     *     If payment is not found
     * @response 400
     *     If payment is already completed
     * @response 200
     *     On success
     */
    public function pay(Request $request): Response
    {
        $payment_id = $request->param('id', '');
        $payment = models\Payment::find($payment_id);

        if (!$payment) {
            return Response::notFound('not_found.phtml');
        }

        if ($payment->completed_at) {
            return Response::badRequest();
        }

        $response = Response::ok('stripe/redirection.phtml', [
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
     * @response 200
     */
    public function succeeded(Request $request): Response
    {
        $response = Response::ok('payments/succeeded.phtml');
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
        if (!$account) {
            return $response;
        }

        $ongoing_payment = $account->ongoingPayment();
        if (!$ongoing_payment || $ongoing_payment->is_paid || !$ongoing_payment->session_id) {
            return $response;
        }

        $stripe_service = new services\Stripe();
        $session = $stripe_service->retrieveSession($ongoing_payment->session_id);
        // @phpstan-ignore-next-line
        if ($session->payment_intent->status === 'succeeded') {
            $ongoing_payment->is_paid = true;
            $ongoing_payment->save();
        }

        return $response;
    }

    /**
     * Handle the cancelation redirection from Stripe.
     *
     * @response 200
     */
    public function canceled(Request $request): Response
    {
        $response = Response::ok('payments/canceled.phtml');
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
        if (!$account) {
            return $response;
        }

        $ongoing_payment = $account->ongoingPayment();
        if (!$ongoing_payment || $ongoing_payment->is_paid || !$ongoing_payment->session_id) {
            return $response;
        }

        $stripe_service = new services\Stripe();
        $session = $stripe_service->retrieveSession($ongoing_payment->session_id);
        $payment_intent = $session->payment_intent;
        // see statuses lifecycle at https://stripe.com/docs/payments/intents#intent-statuses
        if (
            $payment_intent instanceof \Stripe\PaymentIntent &&
            $payment_intent->status !== 'processing' &&
            $payment_intent->status !== 'succeeded'
        ) {
            try {
                $payment_intent->cancel();
            } catch (\Stripe\Exception\ApiErrorException $e) {
                // do nothing on purpose: the payment was already canceled
                // by Stripe on their side.
            }

            $ongoing_payment->remove();
        }

        return $response;
    }
}
