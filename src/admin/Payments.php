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
    public function index($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login');
        }

        $year = $request->param('year', \Minz\Time::now()->format('Y'));
        $payment_dao = new models\dao\Payment();
        $raw_payments = $payment_dao->listByYear($year);
        $payments_by_months = [];
        $payments = [];
        foreach ($raw_payments as $raw_payment) {
            $payment = new models\Payment($raw_payment);
            $month = intval($payment->created_at->format('n'));
            $payments_by_months[$month][] = $payment;
            $payments[] = $payment;
        }

        $format = $request->param('format', 'html');
        if ($format === 'csv') {
            return \Minz\Response::ok('admin/payments/index.txt', [
                'year' => $year,
                'payments' => $payments,
            ]);
        } elseif ($format === 'recettes') {
            return \Minz\Response::ok('admin/payments/recettes.phtml', [
                'year' => $year,
                'payments' => $payments,
            ]);
        } else {
            return \Minz\Response::ok('admin/payments/index.phtml', [
                'year' => $year,
                'payments_by_months' => $payments_by_months,
            ]);
        }
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
        $email = utils\Email::sanitize($request->param('email', ''));
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

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
            return \Minz\Response::badRequest('admin/payments/init.phtml', [
                'countries' => utils\Countries::listSorted(),
                'type' => $type,
                'email' => $email,
                'company_vat_number' => $company_vat_number,
                'amount' => $amount,
                'address' => $address,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $account_dao = new models\dao\Account();
        $db_account = $account_dao->findBy(['email' => $email]);
        if ($db_account) {
            $account = new models\Account($db_account);
        } else {
            $account = models\Account::init($email);
        }
        $account->setAddress($address);
        $account_dao->save($account);

        if ($type === 'common_pot') {
            $payment = models\Payment::initCommonPotFromAccount($account, $amount);
        } elseif ($type === 'subscription_month') {
            $payment = models\Payment::initSubscriptionFromAccount($account, 'month');
        } elseif ($type === 'subscription_year') {
            $payment = models\Payment::initSubscriptionFromAccount($account, 'year');
        } else {
            return \Minz\Response::badRequest('admin/payments/init.phtml', [
                'countries' => utils\Countries::listSorted(),
                'type' => $type,
                'email' => $email,
                'company_vat_number' => $company_vat_number,
                'amount' => $amount,
                'address' => $address,
                'errors' => [
                    'type' => 'Le type de paiement est invalide',
                ],
            ]);
        }

        if ($company_vat_number) {
            $payment->company_vat_number = trim($company_vat_number);
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
                'errors' => $errors,
            ]);
        }

        $payment_dao = new models\dao\Payment();
        $payment->invoice_number = models\Payment::generateInvoiceNumber();
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
