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
        $payment_dao = new models\dao\Payment();
        $payment_id = $request->param('id');
        $raw_payment = $payment_dao->find($payment_id);
        if (!$raw_payment) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        $payment = new models\Payment($raw_payment);
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
        return \Minz\Response::ok('payments/succeeded.phtml');
    }

    /**
     * Handle the cancelation redirection from Stripe.
     *
     * @return \Minz\Response
     */
    public function canceled()
    {
        return \Minz\Response::ok('payments/canceled.phtml');
    }
}
