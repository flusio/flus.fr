<?php

namespace Website\services;

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
     *
     * @param \Website\models\Payment $payment
     * @param string $name
     * @param string $success_url
     * @param string $cancel_url
     *
     * @return ?\Stripe\Checkout\Session
     */
    public function createSession($payment, $name, $success_url, $cancel_url)
    {
        $account = $payment->account();

        if (!$account) {
            return null;
        }

        return \Stripe\Checkout\Session::create([
            'customer_email' => $account->email,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => $name,
                'amount' => $payment->amount,
                'currency' => 'eur',
                'quantity' => $payment->quantity,
            ]],
            'success_url' => $success_url,
            'cancel_url' => $cancel_url,
        ]);
    }

    /**
     * Retrieve a Stripe checkout session
     *
     * @param string $session_id
     *
     * @return \Stripe\Checkout\Session
     */
    public function retrieveSession($session_id)
    {
        return \Stripe\Checkout\Session::retrieve([
            'id' => $session_id,
            'expand' => ['payment_intent'],
        ]);
    }
}
