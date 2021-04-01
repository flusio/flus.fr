<?php

namespace Website\controllers\cli;

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
        $payments = models\Payment::listBy([
            'completed_at' => null,
            'is_paid' => 1,
        ]);
        $number_payments = count($payments);

        foreach ($payments as $payment) {
            $payment->complete(\Minz\Time::now());
            $payment->save();

            $account = $payment->account();
            if ($account && $payment->type === 'subscription') {
                $account->extendSubscription($payment->frequency);
                $account->save();
            }

            $invoice_pdf_service = new services\InvoicePDF($payment);
            $invoice_pdf_service->createPDF($payment->invoiceFilepath());

            $email = $payment->account()->email;
            $invoice_mailer->sendInvoice($email, $payment->invoiceFilepath());
        }

        if ($number_payments > 0) {
            return \Minz\Response::text(200, "{$number_payments} payments completed");
        } else {
            return \Minz\Response::text(200, '');
        }
    }
}
