<?php

namespace Website\api;

use Website\models;
use Website\services;

class Payments
{
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
}
