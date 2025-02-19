<?php

namespace Website\services;

use Website\models;

/**
 * This class allows an easy use of the Stripe service.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Stripe
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(\Minz\Configuration::$application['stripe_private_key']);
    }

    /**
     * Create and return a Stripe checkout session
     */
    public function createSession(
        models\Payment $payment,
        string $name,
        string $success_url,
        string $cancel_url
    ): ?\Stripe\Checkout\Session {
        $account = $payment->account();

        if (!$account) {
            return null;
        }

        return \Stripe\Checkout\Session::create([
            'customer_email' => $account->email,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'quantity' => $payment->quantity,
                'price_data' => [
                    'unit_amount' => $payment->amount,
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $name,
                    ],
                ],
            ]],
            'mode' => 'payment',
            'success_url' => $success_url,
            'cancel_url' => $cancel_url,
        ]);
    }

    /**
     * Retrieve a Stripe checkout session
     */
    public function retrieveSession(string $session_id): \Stripe\Checkout\Session
    {
        return \Stripe\Checkout\Session::retrieve([
            'id' => $session_id,
            'expand' => ['payment_intent'],
        ]);
    }
}
