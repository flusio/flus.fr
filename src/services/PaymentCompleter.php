<?php

namespace Website\services;

use Website\mailers;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class PaymentCompleter
{
    /**
     * Complete a payment, send an invoice and extend the subscription
     *
     * @param \Website\models\Payment
     */
    public function complete($payment)
    {
        $payment->complete(\Minz\Time::now());
        $payment->save();

        $invoice_pdf_service = new InvoicePDF($payment);
        $invoice_pdf_service->createPDF($payment->invoiceFilepath());

        $account = $payment->account();
        if ($account && $payment->type === 'subscription') {
            $account->extendSubscription($payment->frequency);
            $account->save();
        }

        if ($account) {
            $email = $account->email;
            $invoice_mailer = new mailers\Invoices();
            $invoice_mailer->sendInvoice($email, $payment->invoiceFilepath());
        }
    }
}
