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
    /** @var string */
    private $success_url;

    /** @var string */
    private $cancel_url;

    /**
     * @param string $success_url
     * @param string $cancel_url
     */
    public function __construct($success_url, $cancel_url)
    {
        \Stripe\Stripe::setApiKey(\Minz\Configuration::$application['stripe_private_key']);

        $this->success_url = $success_url;
        $this->cancel_url = $cancel_url;
    }

    /**
     * Return a Stripe checkout session
     *
     * @param \Website\models\Payment $payment
     * @param \string $name
     *
     * @return \Stripe\Checkout\Session
     */
    public function createSession($payment, $name)
    {
        return \Stripe\Checkout\Session::create([
            'customer_email' => $payment->email,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => $name,
                'amount' => $payment->amount,
                'currency' => 'eur',
                'quantity' => 1,
            ]],
            'success_url' => $this->success_url,
            'cancel_url' => $this->cancel_url,
        ]);
    }
}
