<?php

namespace Website\controllers\admin\payments;

use Website\utils;
use Website\models;

/**
 * Show the admin main page
 *
 * @return \Minz\Response
 */
function index()
{
    $current_user = utils\currentUser();
    if (!$current_user) {
        return \Minz\Response::redirect('login');
    }

    $year = \Minz\Time::now()->format('Y');
    $payment_dao = new models\dao\Payment();
    $raw_payments = $payment_dao->listByYear($year);
    $payments_by_months = [];
    foreach ($raw_payments as $raw_payment) {
        $payment = new models\Payment($raw_payment);
        $month = intval($payment->created_at->format('n'));
        $payments_by_months[$month][] = $payment;
    }

    return \Minz\Response::ok('admin/payments/index.phtml', [
        'year' => $year,
        'payments_by_months' => $payments_by_months,
    ]);
}

/**
 * Display a form to create a payment.
 *
 * @return \Minz\Response
 */
function init()
{
    $current_user = utils\currentUser();
    if (!$current_user) {
        return \Minz\Response::redirect('login', ['from' => 'admin/payments#init']);
    }

    return \Minz\Response::ok('admin/payments/init.phtml', [
        'countries' => utils\Countries::listSorted(),
        'type' => 'common_pot',
        'username' => '',
        'email' => '',
        'amount' => 30,
        'address' => [
            'first_name' => '',
            'last_name' => '',
            'address1' => '',
            'postcode' => '',
            'city' => '',
            'country' => 'FR',
        ],
        'generate_invoice' => true,
    ]);
}

/**
 * Create a payment
 *
 * Parameters are:
 *
 * - `csrf`
 * - `type`, must be either `common_pot`, `subscription_month` or `subscription_year`
 * - `amount`, required if type is set to `common_pot`, it must be a numerical
 *   value between 1 and 1000.
 * - `email`
 * - `username`, optional
 * - `address[first_name]`
 * - `address[last_name]`
 * - `address[address1]`
 * - `address[postcode]`
 * - `address[city]`
 * - `address[country]`, optional (default is `FR`)
 * - `generate_invoice`, optional
 *
 * @param \Minz\Request $request
 *
 * @return \Minz\Response
 */
function create($request)
{
    $current_user = utils\currentUser();
    if (!$current_user) {
        return \Minz\Response::redirect('login', ['from' => 'admin/payments#init']);
    }

    $type = $request->param('type');
    $email = $request->param('email');
    $username = $request->param('username');
    $amount = $request->param('amount', 0);
    $address = $request->param('address', [
        'first_name' => '',
        'last_name' => '',
        'address1' => '',
        'postcode' => '',
        'city' => '',
        'country' => 'FR',
    ]);
    $generate_invoice = $request->param('generate_invoice', false);

    $csrf = new \Minz\CSRF();
    if (!$csrf->validateToken($request->param('csrf'))) {
        return \Minz\Response::badRequest('admin/payments/init.phtml', [
            'countries' => utils\Countries::listSorted(),
            'type' => $type,
            'email' => $email,
            'username' => $username,
            'amount' => $amount,
            'address' => $address,
            'generate_invoice' => $generate_invoice,
            'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
        ]);
    }

    if ($type === 'common_pot') {
        // nothing to do
    } elseif ($type === 'subscription_month') {
        $type = 'subscription';
        $frequency = 'month';
        $amount = 3;
    } elseif ($type === 'subscription_year') {
        $type = 'subscription';
        $frequency = 'year';
        $amount = 30;
    } else {
        return \Minz\Response::badRequest('admin/payments/init.phtml', [
            'countries' => utils\Countries::listSorted(),
            'type' => $type,
            'email' => $email,
            'username' => $username,
            'amount' => $amount,
            'address' => $address,
            'generate_invoice' => $generate_invoice,
            'errors' => [
                'type' => 'Le type de paiement est invalide',
            ],
        ]);
    }

    try {
        $payment = models\Payment::init($type, $email, $amount, $address);

        if ($type === 'subscription') {
            $payment->setProperty('frequency', $frequency);
        }

        if ($username) {
            $payment->setProperty('username', trim($username));
        }

        if ($generate_invoice) {
            $payment->setProperty('invoice_number', models\Payment::generateInvoiceNumber());
        }
    } catch (\Minz\Errors\ModelPropertyError $e) {
        return \Minz\Response::badRequest('payments/init.phtml', [
            'countries' => utils\Countries::listSorted(),
            'type' => $type,
            'email' => $email,
            'username' => $username,
            'amount' => $amount,
            'address' => $address,
            'generate_invoice' => $generate_invoice,
            'errors' => [
                $e->property() => formatPaymentError($e),
            ],
        ]);
    }

    $payment_dao = new models\dao\Payment();
    $payment_id = $payment_dao->save($payment);

    return \Minz\Response::redirect('admin', ['status' => 'payment_created']);
}

/**
 * Display a payment
 *
 * Parameter is:
 *
 * - `id` of the Payment
 *
 * @param \Minz\Request $request
 *
 * @return \Minz\Response
 */
function show($request)
{
    $current_user = utils\currentUser();
    if (!$current_user) {
        return \Minz\Response::redirect('login', ['from' => 'admin/payments#index']);
    }

    $payment_dao = new models\dao\Payment();
    $payment_id = $request->param('id');
    $raw_payment = $payment_dao->find($payment_id);
    if (!$raw_payment) {
        return \Minz\Response::notFound('not_found.phtml');
    }

    $payment = new models\Payment($raw_payment);
    return \Minz\Response::ok('admin/payments/show.phtml', [
        'completed_at' => \Minz\Time::now(),
        'payment' => $payment,
    ]);
}

/**
 * Complete a payment
 *
 * Parameters are:
 *
 * - `id` of the Payment
 * - `completed_at`
 *
 * @param \Minz\Request $request
 *
 * @return \Minz\Response
 */
function complete($request)
{
    $current_user = utils\currentUser();
    if (!$current_user) {
        return \Minz\Response::redirect('login', ['from' => 'admin/payments#index']);
    }

    $payment_dao = new models\dao\Payment();
    $payment_id = $request->param('id');
    $raw_payment = $payment_dao->find($payment_id);
    if (!$raw_payment) {
        return \Minz\Response::notFound('not_found.phtml');
    }

    $payment = new models\Payment($raw_payment);
    if ($payment->completed_at) {
        return \Minz\Response::badRequest('admin/payments/show.phtml', [
            'payment' => $payment,
            'error' => 'Ce paiement a déjà été confirmé… qu’est-ce que vous essayez de faire ?',
        ]);
    }

    $completed_at = $request->param('completed_at');
    $completed_at = date_create_from_format('Y-m-d', $completed_at);

    $csrf = new \Minz\CSRF();
    if (!$csrf->validateToken($request->param('csrf'))) {
        return \Minz\Response::badRequest('admin/payments/show.phtml', [
            'completed_at' => $completed_at,
            'payment' => $payment,
            'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
        ]);
    }

    $payment->complete($completed_at);
    $payment_dao->save($payment);

    @unlink($payment->invoiceFilepath());

    return \Minz\Response::redirect('admin', [
        'status' => 'payment_completed',
    ]);
}

/**
 * Destroy a payment
 *
 * Parameter is:
 *
 * - `id` of the Payment
 *
 * @param \Minz\Request $request
 *
 * @return \Minz\Response
 */
function destroy($request)
{
    $current_user = utils\currentUser();
    if (!$current_user) {
        return \Minz\Response::redirect('login', ['from' => 'admin/payments#index']);
    }

    $payment_dao = new models\dao\Payment();
    $payment_id = $request->param('id');
    $raw_payment = $payment_dao->find($payment_id);
    if (!$raw_payment) {
        return \Minz\Response::notFound('not_found.phtml');
    }

    $payment = new models\Payment($raw_payment);
    if ($payment->completed_at) {
        return \Minz\Response::badRequest('admin/payments/show.phtml', [
            'payment' => $payment,
            'error' => 'Ce paiement a déjà été confirmé… qu’est-ce que vous essayez de faire ?',
        ]);
    }

    if ($payment->invoice_number) {
        return \Minz\Response::badRequest('admin/payments/show.phtml', [
            'payment' => $payment,
            'error' => 'Ce paiement est associé à une facture et ne peut être supprimé.',
        ]);
    }

    $csrf = new \Minz\CSRF();
    if (!$csrf->validateToken($request->param('csrf'))) {
        return \Minz\Response::badRequest('admin/payments/show.phtml', [
            'completed_at' => \Minz\Time::now(),
            'payment' => $payment,
            'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
        ]);
    }

    $payment_dao->delete($payment->id);

    return \Minz\Response::redirect('admin', [
        'status' => 'payment_deleted',
    ]);
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
    } elseif ($property === 'address_country') {
        return 'Le pays que vous avez renseigné est invalide.';
    } else {
        throw $error;
    }
}
