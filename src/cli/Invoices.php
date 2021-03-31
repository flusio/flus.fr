<?php

namespace Website\cli;

use Website\mailers;
use Website\models;
use Website\services;

class Invoices
{
    /**
     * Send (and generate if needed) a PDF invoice by email
     *
     * Parameter is:
     *
     * - `id`, the id of the payment
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function sendPdf($request)
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $request->param('id');
        $raw_payment = $payment_dao->find($payment_id);
        if (!$raw_payment) {
            return \Minz\Response::text(404, 'Le paiement n’existe pas.');
        }

        $payment = new models\Payment($raw_payment);
        if (!$payment->invoice_number) {
            return \Minz\Response::text(400, 'Ce paiement n’a pas de numéro de facture associé.');
        }

        if (!$payment->invoiceExists()) {
            $invoice_pdf_service = new services\InvoicePDF($payment);
            $invoice_pdf_service->createPDF($payment->invoiceFilepath());
        }

        $invoice_mailer = new mailers\Invoices();
        $email = $payment->account()->email;
        $result = $invoice_mailer->sendInvoice($email, $payment->invoiceFilepath());

        if ($result) {
            return \Minz\Response::text(
                200,
                "La facture {$payment->invoice_number} a été envoyée à l’adresse {$email}."
            );
        } else {
            return \Minz\Response::text(500, 'La facture n’a pas pu être envoyée.'); // @codeCoverageIgnore
        }
    }
}
