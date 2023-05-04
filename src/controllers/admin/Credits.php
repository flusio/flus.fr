<?php

namespace Website\controllers\admin;

use Minz\Request;
use Minz\Response;
use Website\utils;
use Website\models;

class Credits
{
    /**
     * Display a form to create a payment.
     *
     * @request_param string credited_payment_id
     *
     * @response 404 if no payment corresponding to credited_payment_id
     * @response 200 on success
     */
    public function init(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login', ['from' => 'admin/payments#init']);
        }

        $credited_payment_id = $request->param('credited_payment_id');
        $credited_payment = models\Payment::find($credited_payment_id);
        if (!$credited_payment) {
            return Response::notFound('not_found.phtml');
        }

        $already_credited = models\Payment::existsBy([
            'credited_payment_id' => $credited_payment->id,
        ]);

        return Response::ok('admin/credits/init.phtml', [
            'credited_payment' => $credited_payment,
            'already_credited' => $already_credited,
        ]);
    }

    /**
     * Create a credit payment
     *
     * @request_param string csrf
     * @request_param string credited_payment_id
     *
     * @response 404
     *     if no payment corresponding to credited_payment_id
     * @response 400
     *     if csrf is invalid, if payment has already been credited or if
     *     payment type is credit
     * @response 302 /admin
     *     on success
     */
    public function create(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login', ['from' => 'admin/payments#init']);
        }

        $credited_payment_id = $request->param('credited_payment_id');
        $credited_payment = models\Payment::find($credited_payment_id);
        if (!$credited_payment) {
            return Response::notFound('not_found.phtml');
        }

        $already_credited = models\Payment::existsBy([
            'credited_payment_id' => $credited_payment->id,
        ]);

        if ($already_credited) {
            return Response::badRequest('admin/credits/init.phtml', [
                'credited_payment' => $credited_payment,
                'already_credited' => $already_credited,
            ]);
        }

        if ($credited_payment->type === 'credit') {
            return Response::badRequest('admin/credits/init.phtml', [
                'credited_payment' => $credited_payment,
                'already_credited' => $already_credited,
            ]);
        }

        if (!\Minz\Csrf::validate($request->param('csrf'))) {
            return Response::badRequest('admin/credits/init.phtml', [
                'credited_payment' => $credited_payment,
                'already_credited' => $already_credited,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $payment = models\Payment::initCreditFromPayment($credited_payment);
        $payment->invoice_number = models\Payment::generateInvoiceNumber();
        $payment->save();

        return Response::redirect('admin', ['status' => 'payment_credited']);
    }
}
