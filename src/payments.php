<?php

namespace Website\controllers\payments;

use Website\models;
use Website\services;

/**
 * Show the page with the common pot form.
 *
 * @return \Minz\Response
 */
function init()
{
    return \Minz\Response::ok('payments/init.phtml', [
        'email' => '',
        'amount' => 3,
        'address' => [
            'first_name' => '',
            'last_name' => '',
            'address1' => '',
            'postcode' => '',
            'city' => '',
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
 *
 * @param \Minz\Request $request
 *
 * @return \Minz\Response
 */
function payCommonPot($request)
{
    $email = $request->param('email');
    $amount = $request->param('amount', 0);
    $address = $request->param('address', [
        'first_name' => '',
        'last_name' => '',
        'address1' => '',
        'postcode' => '',
        'city' => '',
    ]);

    try {
        $payment = models\Payment::init('common_pot', $email, $amount, $address);
    } catch (\Minz\Errors\ModelPropertyError $e) {
        return \Minz\Response::badRequest('payments/init.phtml', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'errors' => [
                $e->property() => formatPaymentError($e),
            ],
        ]);
    }

    $stripe_service = new services\Stripe(
        \Minz\Url::absoluteFor('payments#succeeded'),
        \Minz\Url::absoluteFor('payments#canceled')
    );

    $stripe_session = $stripe_service->createSession(
        $payment,
        'Participation à la cagnotte de Flus'
    );

    $payment_dao = new models\dao\Payment();
    $payment->setProperty('payment_intent_id', $stripe_session->payment_intent);
    $payment->setProperty('session_id', $stripe_session->id);
    $payment_id = $payment_dao->save($payment);

    return \Minz\Response::redirect('payments#pay', [
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
 *
 * The request must be authenticated (basic auth) with the Flus token.
 *
 * @param \Minz\Request $request
 *
 * @return \Minz\Response
 */
function paySubscription($request)
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
    ]);
    $username = $request->param('username', '');

    $frequency = $request->param('frequency');
    if ($frequency === 'month') {
        $amount = 3;
    } elseif ($frequency === 'year') {
        $amount = 30;
    }

    try {
        $payment = models\Payment::init('subscription', $email, $amount, $address);
        $payment->setProperty('username', trim($username));
        $payment->setProperty('frequency', $frequency);
    } catch (\Minz\Errors\ModelPropertyError $e) {
        $output = new \Minz\Output\Text($e->getMessage());
        return new \Minz\Response(400, $output);
    }

    $stripe_service = new services\Stripe(
        $request->param('success_url', \Minz\Url::absoluteFor('payments#succeeded')),
        $request->param('cancel_url', \Minz\Url::absoluteFor('payments#canceled'))
    );

    $period = $payment->frequency === 'month' ? '1 mois' : '1 an';
    $stripe_session = $stripe_service->createSession(
        $payment,
        "Abonnement à Flus ({$period})"
    );

    $payment_dao = new models\dao\Payment();
    $payment->setProperty('payment_intent_id', $stripe_session->payment_intent);
    $payment->setProperty('session_id', $stripe_session->id);
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
function pay($request)
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
function succeeded()
{
    return \Minz\Response::ok('payments/succeeded.phtml');
}

/**
 * Handle the cancelation redirection from Stripe.
 *
 * @return \Minz\Response
 */
function canceled()
{
    return \Minz\Response::ok('payments/canceled.phtml');
}

/**
 * Format a ModelPropertyError as a user-friendly string
 *
 * @param \Minz\Errors\ModelPropertyError $error
 *
 * @throws \Minz\Errors\ModelPropertyError if the property is not supported
 *
 * @return string
 */
function formatPaymentError($error)
{
    $property = $error->property();
    $code = $error->getCode();
    if ($property === 'email') {
        if ($code === \Minz\Errors\ModelPropertyError::PROPERTY_REQUIRED) {
            return 'L’adresse courriel est obligatoire.';
        } else {
            return 'L’adresse courriel que vous avez fourni est invalide.';
        }
    } elseif ($property === 'amount') {
        return 'Le montant doit être compris entre 1 et 1000 €.';
    } elseif ($property === 'address_first_name') {
        return 'Votre prénom est obligatoire.';
    } elseif ($property === 'address_last_name') {
        return 'Votre nom est obligatoire.';
    } elseif ($property === 'address_address1') {
        return 'Votre adresse est obligatoire.';
    } elseif ($property === 'address_postcode') {
        return 'Votre code postal est obligatoire.';
    } elseif ($property === 'address_city') {
        return 'Votre ville est obligatoire.';
    } else {
        throw $error;
    }
}
