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

    try {
        $payment = models\Payment::init($email, $amount, $address);
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
        throw $e;
    }
}
