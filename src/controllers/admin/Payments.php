<?php

namespace Website\controllers\admin;

use Minz\Request;
use Minz\Response;
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
    public function index(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login');
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
            return Response::ok('admin/payments/index.txt', [
                'year' => $year,
                'payments' => $payments,
            ]);
        } elseif ($format === 'recettes') {
            return Response::ok('admin/payments/recettes.phtml', [
                'year' => $year,
                'payments' => $payments,
            ]);
        } else {
            return Response::ok('admin/payments/index.phtml', [
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
    public function init(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login', ['from' => 'admin/payments#init']);
        }

        return Response::ok('admin/payments/init.phtml', [
            'email' => '',
            'amount' => 30,
        ]);
    }

    /**
     * Create a payment
     *
     * @request_param integer amount
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
    public function create(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login', ['from' => 'admin/payments#init']);
        }

        $email = \Minz\Email::sanitize($request->param('email', ''));
        /** @var int */
        $amount = $request->paramInteger('amount', 0);

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return Response::badRequest('admin/payments/init.phtml', [
                'email' => $email,
                'amount' => $amount,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        if (!\Minz\Email::validate($email)) {
            return Response::badRequest('admin/payments/init.phtml', [
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

        $payment = models\Payment::initSubscriptionFromAccount($account, $amount);

        $errors = $payment->validate();
        if ($errors) {
            return Response::badRequest('admin/payments/init.phtml', [
                'email' => $email,
                'amount' => $amount,
                'errors' => $errors,
            ]);
        }

        $payment->invoice_number = models\Payment::generateInvoiceNumber();
        $payment->save();

        return Response::redirect('admin', ['status' => 'payment_created']);
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
    public function show(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login', ['from' => 'admin/payments#index']);
        }

        $payment_id = $request->param('id');
        $payment = models\Payment::find($payment_id);
        if (!$payment) {
            return Response::notFound('not_found.phtml');
        }

        return Response::ok('admin/payments/show.phtml', [
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
    public function confirm(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login', ['from' => 'admin/payments#index']);
        }

        $payment_id = $request->param('id');
        $payment = models\Payment::find($payment_id);
        if (!$payment) {
            return Response::notFound('not_found.phtml');
        }

        if ($payment->is_paid) {
            return Response::badRequest('admin/payments/show.phtml', [
                'payment' => $payment,
                'error' => 'Ce paiement a déjà été payé… qu’est-ce que vous essayez de faire ?',
            ]);
        }

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return Response::badRequest('admin/payments/show.phtml', [
                'payment' => $payment,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $payment->is_paid = true;
        $payment->save();

        $invoice_filepath = $payment->invoiceFilepath();
        if ($invoice_filepath) {
            @unlink($invoice_filepath);
        }

        return Response::redirect('admin', [
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
    public function destroy(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login', ['from' => 'admin/payments#index']);
        }

        $payment_id = $request->param('id');
        $payment = models\Payment::find($payment_id);
        if (!$payment) {
            return Response::notFound('not_found.phtml');
        }

        if ($payment->is_paid) {
            return Response::badRequest('admin/payments/show.phtml', [
                'completed_at' => \Minz\Time::now(),
                'payment' => $payment,
                'error' => 'Ce paiement a déjà été payé… qu’est-ce que vous essayez de faire ?',
            ]);
        }

        if ($payment->invoice_number) {
            return Response::badRequest('admin/payments/show.phtml', [
                'completed_at' => \Minz\Time::now(),
                'payment' => $payment,
                'error' => 'Ce paiement est associé à une facture et ne peut être supprimé.',
            ]);
        }

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return Response::badRequest('admin/payments/show.phtml', [
                'completed_at' => \Minz\Time::now(),
                'payment' => $payment,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        models\Payment::delete($payment->id);

        return Response::redirect('admin', [
            'status' => 'payment_deleted',
        ]);
    }
}
