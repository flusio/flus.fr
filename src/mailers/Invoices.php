<?php

namespace Website\mailers;

/**
 * The invoices mailer allows to send invoice by email.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Invoices extends \Minz\Mailer
{
    /**
     * @param string $to A valid email address
     * @param string $invoices_path A path to an existing invoice PDF
     *
     * @return boolean
     */
    public function sendInvoice($to, $invoice_path)
    {
        $subject = '[Flus] Reçu pour votre paiement';
        $this->setBody(
            'mailers/invoices/send_invoice.phtml',
            'mailers/invoices/send_invoice.txt',
        );
        $this->mailer->addAttachment($invoice_path);
        return $this->send($to, $subject);
    }
}
