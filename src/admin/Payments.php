<?php

namespace Website\admin;

use Website\utils;
use Website\models;

class Payments
{
    /**
     * Show the admin main page
     *
     * @return \Minz\Response
     */
    public function index()
    {
        if (!utils\CurrentUser::isAdmin()) {
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
    public function init()
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#init']);
        }

        return \Minz\Response::ok('admin/payments/init.phtml', [
            'countries' => utils\Countries::listSorted(),
            'type' => 'common_pot',
            'email' => '',
            'company_vat_number' => '',
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
     * - `company_vat_number`, optional
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
    public function create($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#init']);
        }

        $type = $request->param('type');
        $email = $request->param('email');
        $company_vat_number = $request->param('company_vat_number');
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
                'company_vat_number' => $company_vat_number,
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
                'company_vat_number' => $company_vat_number,
                'amount' => $amount,
                'address' => $address,
                'generate_invoice' => $generate_invoice,
                'errors' => [
                    'type' => 'Le type de paiement est invalide',
                ],
            ]);
        }

        $payment = models\Payment::init($type, $email, $amount, $address);

        if ($type === 'subscription') {
            $payment->frequency = $frequency;
        }

        if ($company_vat_number) {
            $payment->company_vat_number = trim($company_vat_number);
        }

        if ($generate_invoice) {
            $payment->invoice_number = models\Payment::generateInvoiceNumber();
        }

        $errors = $payment->validate();
        if ($errors) {
            return \Minz\Response::badRequest('admin/payments/init.phtml', [
                'countries' => utils\Countries::listSorted(),
                'type' => $type,
                'email' => $email,
                'company_vat_number' => $company_vat_number,
                'amount' => $amount,
                'address' => $address,
                'generate_invoice' => $generate_invoice,
                'errors' => $errors,
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
    public function show($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#index']);
        }

        $payment_dao = new models\dao\Payment();
        $payment_id = $request->param('id');
        $db_payment = $payment_dao->find($payment_id);
        if (!$db_payment) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        $payment = new models\Payment($db_payment);
        return \Minz\Response::ok('admin/payments/show.phtml', [
            'payment' => $payment,
        ]);
    }

    /**
     * Confirm a payment as paid
     *
     * Parameters are:
     *
     * - `id` of the Payment
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function confirm($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#index']);
        }

        $payment_dao = new models\dao\Payment();
        $payment_id = $request->param('id');
        $db_payment = $payment_dao->find($payment_id);
        if (!$db_payment) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        $payment = new models\Payment($db_payment);
        if ($payment->is_paid) {
            return \Minz\Response::badRequest('admin/payments/show.phtml', [
                'payment' => $payment,
                'error' => 'Ce paiement a déjà été payé… qu’est-ce que vous essayez de faire ?',
            ]);
        }

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
            return \Minz\Response::badRequest('admin/payments/show.phtml', [
                'payment' => $payment,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $payment->is_paid = true;
        $payment_dao->save($payment);

        @unlink($payment->invoiceFilepath());

        return \Minz\Response::redirect('admin', [
            'status' => 'payment_confirmed',
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
    public function destroy($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#index']);
        }

        $payment_dao = new models\dao\Payment();
        $payment_id = $request->param('id');
        $raw_payment = $payment_dao->find($payment_id);
        if (!$raw_payment) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        $payment = new models\Payment($raw_payment);
        if ($payment->is_paid) {
            return \Minz\Response::badRequest('admin/payments/show.phtml', [
                'completed_at' => \Minz\Time::now(),
                'payment' => $payment,
                'error' => 'Ce paiement a déjà été payé… qu’est-ce que vous essayez de faire ?',
            ]);
        }

        if ($payment->invoice_number) {
            return \Minz\Response::badRequest('admin/payments/show.phtml', [
                'completed_at' => \Minz\Time::now(),
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
}
