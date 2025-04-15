<?php

namespace Website\controllers;

use Minz\Request;
use Minz\Response;
use Website\models;

class Stripe
{
    /**
     * Handle the checkout.session.completed event from Stripe
     *
     * @see https://stripe.com/docs/payments/checkout/fulfillment#webhooks
     *
     * @request_param string @input
     * @request_header string HTTP_STRIPE_SIGNATURE
     *
     * @response 400
     *     If an error occurs during processing the Stripe request
     * @response 200
     *     On success
     */
    public function hooks(Request $request): Response
    {
        $payload = $request->param('@input', '');
        $signature = $request->header('HTTP_STRIPE_SIGNATURE');
        $hook_secret = \Minz\Configuration::$application['stripe_webhook_secret'];

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $signature, $hook_secret);
        } catch (\UnexpectedValueException $e) {
            \Minz\Log::error($e->getMessage());
            return Response::badRequest();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            \Minz\Log::error($e->getMessage());
            return Response::badRequest();
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            if (!($session instanceof \Stripe\Checkout\Session)) {
                \Minz\Log::error('Stripe checkout.session.completed data object must be a Checkout Session object.');
                return Response::badRequest();
            }

            $payment = models\Payment::findBy([
                'payment_intent_id' => $session->payment_intent,
            ]);

            if (!$payment) {
                \Minz\Log::warning("Payment {$session->payment_intent} completed, not in database.");
                return Response::ok();
            }

            if ($payment->is_paid) {
                // We already know that the payment is paid
                return Response::ok();
            }

            $payment->is_paid = true;
            $payment->save();
        }

        return Response::ok();
    }
}
