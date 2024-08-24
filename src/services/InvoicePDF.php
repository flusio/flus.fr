<?php

namespace Website\services;

use Minz\Output\ViewHelpers;
use Website\models;
use Website\utils;

/**
 * Generate a PDF invoice for a given payment.
 *
 * @phpstan-type InvoicePurchase array{
 *     'description': string,
 *     'quantity': string,
 *     'price': string,
 *     'total': string,
 * }
 *
 * @phpstan-type InvoiceTotal array{
 *     'ht': string,
 *     'tva': 'non applicable',
 *     'ttc': string,
 * }
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class InvoicePDF extends \FPDF
{
    public string $logo;

    /** @var array<string, string> */
    public array $global_info;

    /** @var string[] */
    public array $customer;

    /** @var InvoicePurchase[] */
    public array $purchases;

    /** @var InvoiceTotal */
    public array $total_purchases;

    public function __construct(models\Payment $payment)
    {
        parent::__construct();

        $account = models\Account::find($payment->account_id);

        if (!$account) {
            throw new \RuntimeException("Can’t build invoice for payment {$payment->id} (no account).");
        }

        $this->logo = \Minz\Configuration::$app_path . '/public/static/logo-512px.png';

        $established_at = ViewHelpers::formatDate($payment->created_at, 'dd MMMM yyyy');
        $this->global_info = [
            'N° facture' => $payment->invoice_number ?? '',
            'Établie le' => $established_at,
        ];

        if ($payment->type === 'credit') {
            if ($payment->completed_at) {
                $this->global_info['Créditée le'] = ViewHelpers::formatDate($payment->completed_at, 'dd MMMM yyyy');
            } else {
                $this->global_info['Créditée le'] = 'à créditer';
            }
        } else {
            if ($payment->completed_at) {
                $this->global_info['Payée le'] = ViewHelpers::formatDate($payment->completed_at, 'dd MMMM yyyy');
            } else {
                $this->global_info['Payée le'] = 'à payer';
            }
        }

        if ($account->company_vat_number) {
            $this->global_info['N° TVA client'] = $account->company_vat_number;
        }

        $address = $account->address();
        $this->customer = [];
        if ($account->entity_type === 'natural') {
            $this->customer[] = $address['first_name'] . ' ' . $address['last_name'];
        } else {
            $this->customer[] = $address['legal_name'];
        }

        if ($address['address1']) {
            $this->customer[] = $address['address1'];
            $this->customer[] = $address['postcode'] . ' ' . $address['city'];
            $this->customer[] = utils\Countries::codeToLabel($address['country']);
        };

        $amount = $payment->amount / 100 . ' €';
        $total_amount = $payment->totalAmount() / 100 . ' €';

        $this->total_purchases = [
            'ht' => $total_amount,
            'tva' => 'non applicable',
            'ttc' => $total_amount,
        ];

        if ($payment->type === 'common_pot') {
            $this->purchases = [
                [
                    'description' => "Participation à la cagnotte commune\nde Flus",
                    'quantity' => (string) $payment->quantity,
                    'price' => $amount,
                    'total' => $total_amount,
                ],
            ];
        } elseif ($payment->type === 'subscription') {
            $this->purchases = [
                [
                    'description' => "Renouvellement d'un abonnement\nde 1 an à Flus",
                    'quantity' => (string) $payment->quantity,
                    'price' => $amount,
                    'total' => $total_amount,
                ],
            ];
        } elseif ($payment->type === 'credit') {
            $credited_payment = models\Payment::find($payment->credited_payment_id ?? '');

            if (!$credited_payment) {
                throw new \RuntimeException(
                    "Can’t build invoice for payment {$payment->id} (credited payment doesn’t exist)."
                );
            }

            $invoice_number = $credited_payment->invoice_number;
            $this->purchases = [
                [
                    'description' => "Remboursement de la facture\n{$invoice_number}",
                    'quantity' => (string) $payment->quantity,
                    'price' => $amount,
                    'total' => $total_amount,
                ],
            ];
        }
    }

    /**
     * Create a PDF at the given path.
     */
    public function createPDF(string $filepath): void
    {
        $this->AddPage();
        $this->SetFont('helvetica', '', 12);
        $this->SetFillColor(225);

        $this->Image($this->logo, 20, 20, 60);

        $this->addGlobalInformation($this->global_info, 20);
        $this->addCustomerInformation($this->customer, $this->GetY());
        $this->addPurchases($this->purchases, $this->GetY() + 20);
        $this->addTotalPurchases($this->total_purchases, $this->GetY() + 20);

        // Make sure that the parent directories exist
        $dirname = pathinfo($filepath, PATHINFO_DIRNAME);
        if ($dirname) {
            @mkdir($dirname, 0775, true);
        }

        $this->Output('F', $filepath);
    }

    /**
     * @param array<string, string> $infos
     */
    private function addGlobalInformation(array $infos, int $y_position): void
    {
        $this->SetY($y_position);
        foreach ($infos as $info_key => $info_value) {
            $this->SetX(-100);
            $this->SetFont('', '');
            $this->Cell(40, 10, $this->pdfDecode($info_key), 0, 0);
            $this->SetFont('', 'B');
            $this->Cell(0, 10, $this->pdfDecode($info_value), 0, 1);
        }
    }

    /**
     * @param string[] $infos
     */
    private function addCustomerInformation(array $infos, int $y_position): void
    {
        $this->SetY($y_position);
        $this->SetX(-100);

        $this->SetFont('', '');
        $this->Cell(0, 10, $this->pdfDecode('Identité client'), 0, 1);

        $this->SetFont('', 'B');
        foreach ($infos as $info) {
            $this->SetX(-100);
            $this->Cell(0, 5, $this->pdfDecode($info), 0, 1);
        }
    }

    /**
     * @param InvoicePurchase[] $purchases
     */
    private function addPurchases(array $purchases, int $y_position): void
    {
        $this->SetXY(20, $y_position);
        $this->SetFont('', 'B');
        $this->Cell(90, 10, 'Description', 0, 0, '', true);
        $this->Cell(25, 10, $this->pdfDecode('Quantité'), 0, 0, '', true);
        $this->Cell(25, 10, 'Prix HT', 0, 0, '', true);
        $this->Cell(25, 10, 'Total', 0, 1, '', true);

        $this->SetFont('', '');
        $this->SetXY(20, $this->GetY() + 5);
        foreach ($purchases as $purchase) {
            $this->MultiCell(90, 5, $this->pdfDecode($purchase['description']), 0);

            $this->SetXY(110, $this->GetY() - 10);
            $this->Cell(25, 5, $this->pdfDecode($purchase['quantity']), 0, 0);
            $this->Cell(25, 5, $this->pdfDecode($purchase['price']), 0, 0);
            $this->Cell(25, 5, $this->pdfDecode($purchase['total']), 0, 1);

            $this->SetXY(20, $this->GetY() + 10);
        }
    }

    /**
     * @param InvoiceTotal $infos
     */
    private function addTotalPurchases(array $infos, int $y_position): void
    {
        $this->SetY($y_position);
        $this->SetFont('', 'B');

        $this->SetX(135);
        $this->Cell(25, 10, 'Total HT', 0, 0, '', true);
        $this->Cell(25, 10, $this->pdfDecode($infos['ht']), 0, 1);

        $this->SetX(135);
        $this->Cell(25, 10, 'TVA', 0, 0, '', true);
        $this->Cell(25, 10, $this->pdfDecode($infos['tva']), 0, 1);

        $this->SetX(135);
        $this->Cell(25, 10, 'Total TTC', 0, 0, '', true);
        $this->Cell(25, 10, $this->pdfDecode($infos['ttc']), 0, 1);
    }

    // phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function Footer(): void
    {
        $support_email = \Minz\Configuration::$application['support_email'];
        $footer = [
            'Marien Fressinaud Mas de Feix / Flus – 4 bis rue Germain, 38100 Grenoble – ' . $support_email,
            'micro-entreprise – N° Siret 878 196 278 00021 – 878 196 278 R.C.S. Grenoble',
            'TVA non applicable, art. 293 B du CGI',
        ];

        $offset = count($footer) * 5 + 20;
        $this->SetY(-$offset);
        $this->SetFont('', 'I', 10);
        foreach ($footer as $info) {
            $this->Cell(0, 5, $this->pdfDecode($info), 0, 1, 'C');
        }
    }

    private function pdfDecode(string $string): string
    {
        return mb_convert_encoding($string, 'windows-1252', 'utf-8');
    }
}
