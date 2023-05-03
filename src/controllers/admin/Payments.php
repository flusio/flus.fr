<?php

namespace Website\controllers\admin;

use Website\utils;
use Website\models;

class Payments
{
    /**
     * Show the admin main page
     *
     * @request_param string year
     * @request_param string format
     *     The format to render the page. Allowed values are `html` (default),
     *     `csv` and `recettes`
     *
     * @response 302 /admin/login
     *     If user is not connected as an admin
     * @response 200
     *     On success
     */
    public function index($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login');
        }

        $year = $request->param('year', \Minz\Time::now()->format('Y'));
        $payments = models\Payment::listByYear($year);
        $payments_by_months = [];
        foreach ($payments as $payment) {
            $month = intval($payment->created_at->format('n'));
            $payments_by_months[$month][] = $payment;
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
     * @response 302 /admin/login?from=admin/payments#init
     *     If user is not connected as an admin
     * @response 200
     *     On success
     */
    public function init()
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#init']);
        }

        return \Minz\Response::ok('admin/payments/init.phtml', [
            'type' => 'common_pot',
            'email' => '',
            'amount' => 30,
        ]);
    }

    /**
     * Create a payment
     *
     * @request_param string type
     *     Must be either `common_pot`, `subscription_month` or
     *     `subscription_year`
     * @request_param integer amount
     *     Required if type is set to `common_pot`, it must be a numerical
     *     value between 1 and 1000.
     * @request_param string email
     * @request_param string csrf
     *
     * @response 302 /admin/login?from=admin/payments#init
     *     If user is not connected as an admin
     * @response 400
     *     If a parameter is invalid
     * @response 302 /admin
     *     On success
     */
    public function create($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#init']);
        }

        $type = $request->param('type');
        $email = \Minz\Email::sanitize($request->param('email', ''));
        $amount = $request->paramInteger('amount', 0);

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return \Minz\Response::badRequest('admin/payments/init.phtml', [
                'type' => $type,
                'email' => $email,
                'amount' => $amount,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        if (!\Minz\Email::validate($email)) {
            return \Minz\Response::badRequest('admin/payments/init.phtml', [
                'type' => $type,
                'email' => $email,
                'amount' => $amount,
                'errors' => [
                    'email' => 'L’adresse courriel que vous avez fournie est invalide.',
                ],
            ]);
        }

        $account = models\Account::findBy(['email' => $email]);
        if (!$account) {
            $account = new models\Account($email);
            $account->save();
        }

        if ($type === 'common_pot') {
            $payment = models\Payment::initCommonPotFromAccount($account, $amount);
        } elseif ($type === 'subscription_month') {
            $payment = models\Payment::initSubscriptionFromAccount($account, 'month');
        } elseif ($type === 'subscription_year') {
            $payment = models\Payment::initSubscriptionFromAccount($account, 'year');
        } else {
            return \Minz\Response::badRequest('admin/payments/init.phtml', [
                'type' => $type,
                'email' => $email,
                'amount' => $amount,
                'errors' => [
                    'type' => 'Le type de paiement est invalide',
                ],
            ]);
        }

        $errors = $payment->validate();
        if ($errors) {
            return \Minz\Response::badRequest('admin/payments/init.phtml', [
                'type' => $type,
                'email' => $email,
                'amount' => $amount,
                'errors' => $errors,
            ]);
        }

        $payment->invoice_number = models\Payment::generateInvoiceNumber();
        $payment->save();

        return \Minz\Response::redirect('admin', ['status' => 'payment_created']);
    }

    /**
     * Display a payment
     *
     * @request_param string id
     *
     * @response 302 /admin/login?from=admin/payments#index
     *     If user is not connected as an admin
     * @response 404
     *     If the payment doesn't exist
     * @response 200
     *     On success
     */
    public function show($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#index']);
        }

        $payment_id = $request->param('id');
        $payment = models\Payment::find($payment_id);
        if (!$payment) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        return \Minz\Response::ok('admin/payments/show.phtml', [
            'payment' => $payment,
        ]);
    }

    /**
     * Confirm a payment as paid
     *
     * @request_param string id
     * @request_param string csrf
     *
     * @response 302 /admin/login?from=admin/payments#index
     *     If user is not connected as an admin
     * @response 404
     *     If the payment doesn't exist
     * @response 400
     *     If the CSRF is invalid or if payment is already paid
     * @response 302 /admin
     *     On success
     */
    public function confirm($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#index']);
        }

        $payment_id = $request->param('id');
        $payment = models\Payment::find($payment_id);
        if (!$payment) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        if ($payment->is_paid) {
            return \Minz\Response::badRequest('admin/payments/show.phtml', [
                'payment' => $payment,
                'error' => 'Ce paiement a déjà été payé… qu’est-ce que vous essayez de faire ?',
            ]);
        }

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return \Minz\Response::badRequest('admin/payments/show.phtml', [
                'payment' => $payment,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $payment->is_paid = true;
        $payment->save();

        @unlink($payment->invoiceFilepath());

        return \Minz\Response::redirect('admin', [
            'status' => 'payment_confirmed',
        ]);
    }

    /**
     * Destroy a payment
     *
     * @request_param string id
     * @request_param string csrf
     *
     * @response 302 /admin/login?from=admin/payments#index
     *     If user is not connected as an admin
     * @response 404
     *     If the payment doesn't exist
     * @response 400
     *     If the CSRF is invalid or if payment is already paid or is
     *     associated to an invoice number.
     * @response 302 /admin
     *     On success
     */
    public function destroy($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#index']);
        }

        $payment_id = $request->param('id');
        $payment = models\Payment::find($payment_id);
        if (!$payment) {
            return \Minz\Response::notFound('not_found.phtml');
        }

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

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return \Minz\Response::badRequest('admin/payments/show.phtml', [
                'completed_at' => \Minz\Time::now(),
                'payment' => $payment,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        models\Payment::delete($payment->id);

        return \Minz\Response::redirect('admin', [
            'status' => 'payment_deleted',
        ]);
    }
}
