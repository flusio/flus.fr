<?php

namespace Website\mailers;

use Minz\Mailer;

/**
 * The invoices mailer allows to send invoice by email.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Invoices extends Mailer
{
    public function sendInvoice(string $to, string $invoice_path): Mailer\Email
    {
        $email = new Mailer\Email();
        $email->setSubject('[Flus] Reçu pour votre paiement');
        $email->setBody(
            'mailers/invoices/send_invoice.phtml',
            'mailers/invoices/send_invoice.txt',
        );
        $email->addAttachment($invoice_path);

        $this->send($email, to: $to);

        return $email;
    }
}
