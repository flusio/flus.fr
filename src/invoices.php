<?php

namespace Website\controllers\invoices;

use Website\utils;
use Website\models;
use Website\mailers;
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
    $current_user = utils\currentUser();
    $auth_token = $request->header('PHP_AUTH_USER', '');
    $private_key = \Minz\Configuration::$application['flus_private_key'];
    if (!$current_user && !hash_equals($private_key, $auth_token)) {
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

/**
 * Send (and generate if needed) a PDF invoice by email
 *
 * Parameter is:
 *
 * - `id`, the id of the payment
 *
 * This request is only accessible through CLI
 *
 * @param \Minz\Request $request
 *
 * @return \Minz\Response
 */
function send_pdf($request)
{
    $payment_dao = new models\dao\Payment();
    $payment_id = $request->param('id');
    $raw_payment = $payment_dao->find($payment_id);
    if (!$raw_payment) {
        $output = new \Minz\Output\Text("Le paiement n’existe pas.\n");
        return new \Minz\Response(404, $output);
    }

    $payment = new models\Payment($raw_payment);
    if (!$payment->invoice_number) {
        $output = new \Minz\Output\Text("Ce paiement n’a pas de numéro de facture associé.\n");
        return new \Minz\Response(400, $output);
    }

    if (!$payment->invoiceExists()) {
        $invoice_pdf_service = new services\InvoicePDF($payment);
        $invoice_pdf_service->createPDF($payment->invoiceFilepath());
    }

    $invoice_mailer = new mailers\Invoices();
    $result = $invoice_mailer->sendInvoice($payment->email, $payment->invoiceFilepath());

    if ($result) {
        $output = new \Minz\Output\Text(
            "La facture {$payment->invoice_number} a été envoyée à l’adresse {$payment->email}.\n"
        );
        return new \Minz\Response(200, $output);
    } else {
        $output = new \Minz\Output\Text("La facture n’a pas pu être envoyée.\n");
        return new \Minz\Response(500, $output);
    }
}
