<?php

namespace Website\controllers\admin;

use Minz\Request;
use Minz\Response;
use Website\auth;
use Website\models;

class Payments extends BaseController
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
        auth\CurrentUser::requireAdmin();

        $current_year = intval(\Minz\Time::now()->format('Y'));
        $year = $request->parameters->getInteger('year', $current_year);

        $payments = models\Payment::listByYear($year);
        $payments_by_months = [];
        foreach ($payments as $payment) {
            $month = $payment->created_at->format('m');
            $payments_by_months[$month][] = $payment;
        }

        $format = $request->parameters->getString('format', 'html');
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
                'count_free_renewals_per_month' => models\FreeRenewal::countPerMonth($year),
            ]);
        }
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
        auth\CurrentUser::requireAdmin();

        $payment = models\Payment::requireFromRequest($request);

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
        auth\CurrentUser::requireAdmin();

        $payment = models\Payment::requireFromRequest($request);

        if ($payment->is_paid) {
            return Response::badRequest('admin/payments/show.phtml', [
                'payment' => $payment,
                'error' => 'Ce paiement a déjà été payé… qu’est-ce que vous essayez de faire ?',
            ]);
        }

        if (!\Website\Csrf::validate($request->parameters->getString('csrf', ''))) {
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
        auth\CurrentUser::requireAdmin();

        $payment = models\Payment::requireFromRequest($request);

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

        if (!\Website\Csrf::validate($request->parameters->getString('csrf', ''))) {
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
