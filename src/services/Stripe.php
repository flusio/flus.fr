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
     * Initiate a Stripe checkout session and return a Response redirecting to Stripe.
     *
     * @param \Website\models\Payment $payment
     *
     * @return \Minz\Response
     */
    public function pay($payment)
    {
        $stripe_session = \Stripe\Checkout\Session::create([
            'customer_email' => $payment->email,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => 'Participation à la cagnotte de Flus',
                'amount' => $payment->amount,
                'currency' => 'eur',
                'quantity' => 1,
            ]],
            'payment_intent_data' => [
                'metadata' => [
                    'type' => 'cagnotte',
                ],
            ],
            'success_url' => $this->success_url,
            'cancel_url' => $this->cancel_url,
        ]);

        $payment_dao = new models\dao\Payment();
        $payment->setProperty('payment_intent_id', $stripe_session->payment_intent);
        $payment_dao->save($payment);

        $response = \Minz\Response::ok('stripe/redirection.phtml', [
            'stripe_public_key' => \Minz\Configuration::$application['stripe_public_key'],
            'stripe_session_id' => $stripe_session->id,
        ]);
        $response->setContentSecurityPolicy('default-src', "'self' js.stripe.com");
        $response->setContentSecurityPolicy('script-src', "'self' 'unsafe-inline' js.stripe.com");
        return $response;
    }
}
