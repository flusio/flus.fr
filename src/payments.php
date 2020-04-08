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
 * Handle the payement request and redirect to Stripe.
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
function pay($request)
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

    if (!$email) {
        return \Minz\Response::badRequest('payments/init.phtml', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'error_email' => 'L’adresse courriel est obligatoire.',
        ]);
    }

    if (!is_numeric($amount)) {
        return \Minz\Response::badRequest('payments/init.phtml', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'error_amount' => 'Le montant doit être une valeur numérique comprise entre 1 et 1000 €.',
        ]);
    }

    if (!$address['first_name']) {
        return \Minz\Response::badRequest('payments/init.phtml', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'error_address_first_name' => 'Votre prénom est obligatoire.',
        ]);
    }

    if (!$address['last_name']) {
        return \Minz\Response::badRequest('payments/init.phtml', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'error_address_last_name' => 'Votre nom est obligatoire.',
        ]);
    }

    if (!$address['address1']) {
        return \Minz\Response::badRequest('payments/init.phtml', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'error_address_address1' => 'Votre adresse est obligatoire.',
        ]);
    }

    if (!$address['postcode']) {
        return \Minz\Response::badRequest('payments/init.phtml', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'error_address_postcode' => 'Votre code postal est obligatoire.',
        ]);
    }

    if (!$address['city']) {
        return \Minz\Response::badRequest('payments/init.phtml', [
            'email' => $email,
            'amount' => $amount,
            'address' => $address,
            'error_address_city' => 'Votre ville est obligatoire.',
        ]);
    }

    try {
        $payment = models\Payment::init($email, $amount, $address);
    } catch (\Minz\Errors\ModelPropertyError $e) {
        if ($e->property() === 'email') {
            return \Minz\Response::badRequest('payments/init.phtml', [
                'email' => $email,
                'amount' => $amount,
                'address' => $address,
                'error_email' => 'L’adresse courriel que vous avez fourni est invalide.',
            ]);
        } elseif ($e->property() === 'amount') {
            return \Minz\Response::badRequest('payments/init.phtml', [
                'email' => $email,
                'amount' => $amount,
                'address' => $address,
                'error_amount' => 'Le montant doit être compris entre 1 et 1000 €.',
            ]);
        } else {
            throw $e;
        }
    }

    $stripe = new services\Stripe(
        \Minz\Url::absoluteFor('payments#succeeded'),
        \Minz\Url::absoluteFor('payments#canceled')
    );

    return $stripe->pay($payment);
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
