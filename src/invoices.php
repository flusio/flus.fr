<?php

namespace Website\controllers\invoices;

use Website\models;
use Website\services;

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
function download_pdf($request)
{
    $auth_token = $request->header('PHP_AUTH_USER', '');
    $private_key = \Minz\Configuration::$application['flus_private_key'];
    if (!hash_equals($private_key, $auth_token)) {
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

    $invoice_filename = "facture_{$payment->invoice_number}.pdf";

    $invoices_path = \Minz\Configuration::$data_path . '/invoices';
    @mkdir($invoices_path);

    $invoice_path = $invoices_path . '/' . $invoice_filename;
    if (!file_exists($invoice_path)) {
        $invoice_pdf_service = new services\InvoicePDF($payment);
        $invoice_pdf_service->createPDF($invoice_path);
    }

    $output = new \Minz\Output\File($invoice_path);
    $response = new \Minz\Response(200, $output);
    $response->setHeader('Content-Disposition', 'attachment; filename="' . $invoice_filename . '"');
    return $response;
}
