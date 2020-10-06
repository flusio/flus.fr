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
        $is_admin = utils\CurrentUser::isAdmin();
        $auth_token = $request->header('PHP_AUTH_USER', '');
        $private_key = \Minz\Configuration::$application['flus_private_key'];
        if (!$is_admin && !hash_equals($private_key, $auth_token)) {
            return \Minz\Response::unauthorized();
        }

        $payment_dao = new models\dao\Payment();
        $payment_id = $request->param('id');
        $raw_payment = $payment_dao->find($payment_id);
        if (!$raw_payment) {
            return \Minz\Response::notFound();
        }

        $payment = new models\Payment($raw_payment);
        if (!$payment->invoice_number) {
            return \Minz\Response::notFound();
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
