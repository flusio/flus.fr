<?php

namespace Website\cli;

use Website\models;
use Website\services;
use Website\mailers;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Payments
{
    /**
     * @response 200
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function complete($request)
    {
        $invoice_mailer = new mailers\Invoices();
        $account_dao = new models\dao\Account();
        $payment_dao = new \Website\models\dao\Payment();
        $db_payments = $payment_dao->listBy([
            'completed_at' => null,
            'is_paid' => 1,
        ]);
        $number_payments = count($db_payments);

        foreach ($db_payments as $db_payment) {
            $payment = new models\Payment($db_payment);
            $payment->complete(\Minz\Time::now());
            $payment_dao->save($payment);

            $account = $payment->account();
            if ($account) {
                $account->extendSubscription($payment->frequency);
                $account_dao->save($account);
            }

            $invoice_pdf_service = new services\InvoicePDF($payment);
            $invoice_pdf_service->createPDF($payment->invoiceFilepath());

            $invoice_mailer->sendInvoice($payment->email, $payment->invoiceFilepath());
        }

        return \Minz\Response::text(200, "{$number_payments} payments completed");
    }
}
