<?php

namespace Website\controllers;

use Website\models;

class Stripe
{
    /**
     * Handle the checkout.session.completed event from Stripe
     *
     * @see https://stripe.com/docs/payments/checkout/fulfillment#webhooks
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function hooks($request)
    {
        $payload = $request->param('@input');
        $signature = $request->header('HTTP_STRIPE_SIGNATURE');
        $hook_secret = \Minz\Configuration::$application['stripe_webhook_secret'];

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $signature, $hook_secret);
        } catch (\UnexpectedValueException $e) {
            \Minz\Log::error($e->getMessage());
            return \Minz\Response::badRequest();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            \Minz\Log::error($e->getMessage());
            return \Minz\Response::badRequest();
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $payment = models\Payment::findBy([
                'payment_intent_id' => $session->payment_intent,
            ]);

            if (!$payment) {
                \Minz\Log::warning("Payment {$session->payment_intent} completed, not in database.");
                return \Minz\Response::ok();
            }

            $payment->is_paid = true;
            $payment->save();
        }

        return \Minz\Response::ok();
    }
}
