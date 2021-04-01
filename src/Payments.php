<?php

namespace Website;

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
        return $response;
    }
}
