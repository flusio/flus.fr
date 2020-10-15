<?php

namespace Website;

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
