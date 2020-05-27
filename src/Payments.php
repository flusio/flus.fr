<?php

namespace Website;

class Payments
{
    /**
     * Show the page with the common pot form.
     *
     * @return \Minz\Response
     */
    public function init()
    {
        $payment_dao = new models\dao\Payment();
        $common_pot_amount = $payment_dao->findCommonPotRevenue() / 100;
        $available_accounts = floor($common_pot_amount / 3);
        return \Minz\Response::ok('payments/init.phtml', [
            'countries' => utils\Countries::listSorted(),
            'email' => '',
            'amount' => 30,
            'common_pot_amount' => number_format($common_pot_amount, 2, ',', '&nbsp;'),
            'available_accounts' => number_format($available_accounts, 0, ',', '&nbsp;'),
            'address' => [
                'first_name' => '',
                'last_name' => '',
                'address1' => '',
                'postcode' => '',
                'city' => '',
                'country' => 'FR',
            ],
        ]);
    }

    /**
     * Handle the payment request and redirect to Stripe.
     *
     * Parameters are:
     *
     * - `email`
     * - `amount`, it must be a numerical value between 1 and 1000
     * - `address[first_name]`
     * - `address[last_name]`
     * - `address[address1]`
     * - `address[postcode]`
     * - `address[city]`
     * - `address[country]`, optional (default is `FR`)
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function payCommonPot($request)
    {
        $payment_dao = new models\dao\Payment();
        $common_pot_amount = $payment_dao->findCommonPotRevenue() / 100;
        $available_accounts = floor($common_pot_amount / 3);

        $accept_cgv = $request->param('accept_cgv', false);
        $email = $request->param('email');
        $amount = $request->param('amount', 0);
        $address = $request->param('address', [
            'first_name' => '',
            'last_name' => '',
            'address1' => '',
            'postcode' => '',
            'city' => '',
            'country' => 'FR',
        ]);

        if (!$accept_cgv) {
            return \Minz\Response::badRequest('payments/init.phtml', [
                'countries' => utils\Countries::listSorted(),
                'email' => $email,
                'amount' => $amount,
                'address' => $address,
                'common_pot_amount' => number_format($common_pot_amount, 2, ',', '&nbsp;'),
                'available_accounts' => number_format($available_accounts, 0, ',', '&nbsp;'),
                'errors' => [
                    'cgv' => 'Vous devez accepter ces conditions pour participer à la cagnotte.',
                ],
            ]);
        }

        $payment = models\Payment::init('common_pot', $email, $amount, $address);
        $errors = $payment->validate();
        if ($errors) {
            return \Minz\Response::badRequest('payments/init.phtml', [
                'countries' => utils\Countries::listSorted(),
                'email' => $email,
                'amount' => $amount,
                'address' => $address,
                'common_pot_amount' => number_format($common_pot_amount, 2, ',', '&nbsp;'),
                'available_accounts' => number_format($available_accounts, 0, ',', '&nbsp;'),
                'errors' => $errors,
            ]);
        }

        $stripe_service = new services\Stripe(
            \Minz\Url::absoluteFor('Payments#succeeded'),
            \Minz\Url::absoluteFor('Payments#canceled')
        );

        $stripe_session = $stripe_service->createSession(
            $payment,
            'Participation à la cagnotte de Flus'
        );

        $payment->payment_intent_id = $stripe_session->payment_intent;
        $payment->session_id = $stripe_session->id;
        $payment_id = $payment_dao->save($payment);

        return \Minz\Response::redirect('Payments#pay', [
            'id' => $payment_id,
        ]);
    }

    /**
     * Handle the payment request for a subscription and redirect to Stripe.
     *
     * Parameters are:
     *
     * - `email`
     * - `username`
     * - `frequency`, it must be `month` or `year`
     * - `address[first_name]`
     * - `address[last_name]`
     * - `address[address1]`
     * - `address[postcode]`
     * - `address[city]`
     * - `address[country]`, optional (default is `FR`)
     *
     * The request must be authenticated (basic auth) with the Flus token.
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function paySubscription($request)
    {
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!hash_equals($private_key, $auth_token)) {
            return \Minz\Response::unauthorized();
        }

        $email = $request->param('email');
        $amount = $request->param('amount', 0);
        $address = $request->param('address', [
            'first_name' => '',
            'last_name' => '',
            'address1' => '',
            'postcode' => '',
            'city' => '',
            'country' => 'FR',
        ]);
        $username = $request->param('username', '');

        $frequency = $request->param('frequency');
        if ($frequency === 'month') {
            $amount = 3;
        } elseif ($frequency === 'year') {
            $amount = 30;
        }

        $payment = models\Payment::init('subscription', $email, $amount, $address);
        $payment->username = trim($username);
        $payment->frequency = $frequency;

        $errors = $payment->validate();
        if ($errors) {
            $messages = array_column($errors, 'description');
            $output = new \Minz\Output\Text(implode(' ', $messages));
            return new \Minz\Response(400, $output);
        }

        $stripe_service = new services\Stripe(
            $request->param('success_url', \Minz\Url::absoluteFor('Payments#succeeded')),
            $request->param('cancel_url', \Minz\Url::absoluteFor('Payments#canceled'))
        );

        $period = $payment->frequency === 'month' ? '1 mois' : '1 an';
        $stripe_session = $stripe_service->createSession(
            $payment,
            "Abonnement à Flus ({$period})"
        );

        $payment_dao = new models\dao\Payment();
        $payment->payment_intent_id = $stripe_session->payment_intent;
        $payment->session_id = $stripe_session->id;
        $id = $payment_dao->save($payment);

        // need to reload payment to get id and created_at
        $payment = new models\Payment($payment_dao->find($id));
        $json_payment = $payment->toJson();

        $output = new \Minz\Output\Text($json_payment);
        $response = new \Minz\Response(200, $output);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Return information on payment as a Json
     *
     * Parameter is:
     *
     * - `id` of the Payment
     *
     * The request must be authenticated (basic auth) with the Flus token.
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function show($request)
    {
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!hash_equals($private_key, $auth_token)) {
            return \Minz\Response::unauthorized();
        }

        $payment_dao = new models\dao\Payment();
        $payment_id = $request->param('id');
        $raw_payment = $payment_dao->find($payment_id);
        if (!$raw_payment) {
            return \Minz\Response::notFound();
        }

        $payment = new models\Payment($raw_payment);
        $json_payment = $payment->toJson();

        $output = new \Minz\Output\Text($json_payment);
        $response = new \Minz\Response(200, $output);
        $response->setHeader('Content-Type', 'application/json');
        return $response;
    }

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
