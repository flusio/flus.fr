<?php

namespace Website\api;

use Website\models;
use Website\services;
use Website\utils;

class Invoices
{
    /**
     * Serve (and generate if needed) a PDF invoice
     *
     * Parameter is:
     *
     * - `id`, the id of the payment
     *
     * The request must be authenticated (basic auth) with the Flus token.
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function downloadPdf($request)
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $request->param('id');
        $db_payment = $payment_dao->find($payment_id);
        if (!$db_payment) {
            return \Minz\Response::notFound();
        }

        $payment = new models\Payment($db_payment);
        if (!$payment->invoice_number) {
            return \Minz\Response::notFound();
        }

        $is_admin = utils\CurrentUser::isAdmin();
        $user = utils\CurrentUser::get();
        $account_owns = $user && $user['account_id'] === $payment->account_id;
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!$is_admin && !$account_owns && !hash_equals($private_key, $auth_token)) {
            return \Minz\Response::unauthorized();
        }

        $invoices_path = \Minz\Configuration::$data_path . '/invoices';
        @mkdir($invoices_path);

        if (!$payment->invoiceExists()) {
            $invoice_pdf_service = new services\InvoicePDF($payment);
            $invoice_pdf_service->createPDF($payment->invoiceFilepath());
        }

        $output = new \Minz\Output\File($payment->invoiceFilepath());
        $response = new \Minz\Response(200, $output);
        $response->setHeader('Content-Disposition', 'attachment; filename="' . $payment->invoiceFilename() . '"');
        return $response;
    }
}
