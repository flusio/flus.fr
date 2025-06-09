<?php

namespace Website\controllers;

use Minz\Request;
use Minz\Response;
use Website\models;
use Website\services;
use Website\utils;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Invoices
{
    /**
     * Serve (and generate if needed) a PDF invoice
     *
     * @request_param string id
     *     The id of the payment
     *
     * @response 404
     *     If the payment doesn't exist or has no invoice_number
     * @response 401
     *     If the user is not connected or doesn't own the payment
     * @response 200
     *     On success
     */
    public function downloadPdf(Request $request): Response
    {
        $payment_id = $request->parameters->getString('id', '');
        $payment = models\Payment::find($payment_id);

        if (!$payment || !$payment->invoice_number) {
            return Response::notFound();
        }

        $is_admin = utils\CurrentUser::isAdmin();
        $user = utils\CurrentUser::get();
        $account_owns = $user && $user['account_id'] === $payment->account_id;
        if (!$is_admin && !$account_owns) {
            return Response::unauthorized();
        }

        $invoices_path = \Minz\Configuration::$data_path . '/invoices';
        @mkdir($invoices_path);

        $invoice_filepath = $payment->invoiceFilepath();
        if (!$invoice_filepath) {
            return Response::notFound();
        }

        if (!$payment->invoiceExists()) {
            $invoice_pdf_service = new services\InvoicePDF($payment);
            $invoice_pdf_service->createPDF($invoice_filepath);
        }

        $output = new \Minz\Output\File($invoice_filepath);
        $response = new Response(200, $output);
        $response->setHeader('Content-Disposition', 'attachment; filename="' . $payment->invoiceFilename() . '"');
        return $response;
    }
}
