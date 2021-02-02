<?php

namespace Website\admin;

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
    public function init($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#init']);
        }

        $credited_payment_id = $request->param('credited_payment_id');
        $payment_dao = new models\dao\Payment();
        $db_payment = $payment_dao->find($credited_payment_id);
        if (!$db_payment) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        $credited_payment = new models\Payment($db_payment);
        $already_credited = $payment_dao->findBy([
            'credited_payment_id' => $credited_payment->id,
        ]) !== null;

        return \Minz\Response::ok('admin/credits/init.phtml', [
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
    public function create($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login', ['from' => 'admin/payments#init']);
        }

        $credited_payment_id = $request->param('credited_payment_id');
        $payment_dao = new models\dao\Payment();
        $db_payment = $payment_dao->find($credited_payment_id);
        if (!$db_payment) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        $credited_payment = new models\Payment($db_payment);
        $already_credited = $payment_dao->findBy([
            'credited_payment_id' => $credited_payment->id,
        ]) !== null;

        if ($already_credited) {
            return \Minz\Response::badRequest('admin/credits/init.phtml', [
                'credited_payment' => $credited_payment,
                'already_credited' => $already_credited,
            ]);
        }

        if ($credited_payment->type === 'credit') {
            return \Minz\Response::badRequest('admin/credits/init.phtml', [
                'credited_payment' => $credited_payment,
                'already_credited' => $already_credited,
            ]);
        }

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
            return \Minz\Response::badRequest('admin/credits/init.phtml', [
                'credited_payment' => $credited_payment,
                'already_credited' => $already_credited,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $payment = models\Payment::initCreditFromPayment($credited_payment);
        $payment->invoice_number = models\Payment::generateInvoiceNumber();
        $payment_id = $payment_dao->save($payment);

        return \Minz\Response::redirect('admin', ['status' => 'payment_credited']);
    }
}
