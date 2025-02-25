<?php

namespace Website\services;

use Website\mailers;
use Website\models;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class PaymentCompleter
{
    /**
     * Complete a payment, send an invoice and extend the subscription
     */
    public function complete(models\Payment $payment): void
    {
        $payment->complete(\Minz\Time::now());
        $payment->save();

        $account = $payment->account();
        if ($account && $payment->type === 'subscription') {
            $account->extendSubscription();
            $account->save();

            foreach ($account->managedAccounts() as $managed_account) {
                $managed_account->extendSubscription();
                $managed_account->save();
            }
        }

        $invoice_filepath = $payment->invoiceFilepath();

        if ($invoice_filepath) {
            $invoice_pdf_service = new InvoicePDF($payment);
            $invoice_pdf_service->createPDF($invoice_filepath);

            if ($account) {
                $invoice_mailer = new mailers\Invoices();
                try {
                    $invoice_mailer->sendInvoice($account->email, $invoice_filepath);
                } catch (\Minz\Errors\MailerError $e) {
                    \Minz\Log::error("[PaymentCompleter#complete] Can't send {$payment->id} invoice by email.");
                }
            }
        } else {
            \Minz\Log::error("[PaymentCompleter#complete] Payment {$payment->id} has no invoice filepath.");
        }
    }
}
