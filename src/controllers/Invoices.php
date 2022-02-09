<?php

namespace Website\controllers;

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
    public function downloadPdf($request)
    {
        $payment_id = $request->param('id');
        $payment = models\Payment::find($payment_id);

        if (!$payment || !$payment->invoice_number) {
            return \Minz\Response::notFound();
        }

        $is_admin = utils\CurrentUser::isAdmin();
        $user = utils\CurrentUser::get();
        $account_owns = $user && $user['account_id'] === $payment->account_id;
        if (!$is_admin && !$account_owns) {
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
