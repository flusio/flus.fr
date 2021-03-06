<?php

namespace Website\controllers\admin;

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
        $payments = models\Payment::daoToList('listByYear', $year);
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
     * @return \Minz\Response
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
     * Parameters are:
     *
     * - `csrf`
     * - `type`, must be either `common_pot`, `subscription_month` or `subscription_year`
     * - `amount`, required if type is set to `common_pot`, it must be a numerical
     *   value between 1 and 1000.
     * - `email`
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
        $amount = $request->param('amount', 0);

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
            return \Minz\Response::badRequest('admin/payments/init.phtml', [
                'type' => $type,
                'email' => $email,
                'amount' => $amount,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        if (!utils\Email::validate($email)) {
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
            $account = models\Account::init($email);
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

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
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

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
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
