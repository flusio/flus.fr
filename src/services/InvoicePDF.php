<?php

namespace Website\services;

use Website\models;
use Website\utils;

/**
 * Generate a PDF invoice for a given payment.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class InvoicePDF extends \FPDF
{
    /** @var string */
    public $logo;

    /** @var array */
    public $metadata;

    /** @var array */
    public $customer;

    /** @var array */
    public $purchases;

    /** @var array */
    public $total_purchases;

    /** @var array */
    public $footer = [
        'Marien Fressinaud Mas de Feix / Flus – 57 rue du Vercors, 38000 Grenoble – support@flus.io',
        'micro-entreprise – N° Siret 878 196 278 00013 – 878 196 278 R.C.S. Grenoble',
        'TVA non applicable, art. 293 B du CGI',
    ];

    /**
     * @param \Minz\models\Payment
     */
    public function __construct($payment)
    {
        parent::__construct();

        $account = models\Account::find($payment->account_id);

        $this->logo = \Minz\Configuration::$app_path . '/public/static/logo-512px.png';

        $established_at = strftime('%d %B %Y', $payment->created_at->getTimestamp());
        $this->metadata = [
            'N° facture' => $payment->invoice_number,
            'Établie le' => $established_at,
        ];

        if ($payment->type === 'credit') {
            if ($payment->completed_at) {
                $this->metadata['Créditée le'] = strftime('%d %B %Y', $payment->completed_at->getTimestamp());
            } else {
                $this->metadata['Créditée le'] = 'à créditer';
            }
        } else {
            if ($payment->completed_at) {
                $this->metadata['Payée le'] = strftime('%d %B %Y', $payment->completed_at->getTimestamp());
            } else {
                $this->metadata['Payée le'] = 'à payer';
            }
        }

        if ($account->company_vat_number) {
            $this->metadata['N° TVA client'] = $account->company_vat_number;
        }

        $address = $account->address();
        $this->customer = [
            $address['first_name'] . ' ' . $address['last_name'],
        ];

        if ($address['address1']) {
            $this->customer[] = $address['address1'];
            $this->customer[] = $address['postcode'] . ' ' . $address['city'];
            $this->customer[] = utils\Countries::codeToLabel($address['country']);
        };

        $amount = $payment->amount / 100 . ' €';

        $this->total_purchases = [
            'ht' => $amount,
            'tva' => 'non applicable',
            'ttc' => $amount,
        ];

        if ($payment->type === 'common_pot') {
            $this->purchases = [
                [
                    'description' => "Participation à la cagnotte commune\nde Flus",
                    'number' => 1,
                    'price' => $amount,
                    'total' => $amount,
                ],
            ];
        } elseif ($payment->type === 'subscription') {
            $period = $payment->frequency === 'month' ? '1 mois' : '1 an';
            $this->purchases = [
                [
                    'description' => "Renouvellement d'un abonnement\nde " . $period . " à Flus",
                    'number' => 1,
                    'price' => $amount,
                    'total' => $amount,
                ],
            ];
        } elseif ($payment->type === 'credit') {
            $credited_payment = models\Payment::find($payment->credited_payment_id);
            $invoice_number = $credited_payment->invoice_number;
            $this->purchases = [
                [
                    'description' => "Remboursement de la facture\n{$invoice_number}",
                    'number' => 1,
                    'price' => $amount,
                    'total' => $amount,
                ],
            ];
        }
    }

    /**
     * Create a PDF at the given path.
     *
     * @param string $filepath
     */
    public function createPDF($filepath)
    {
        $this->AddPage();
        $this->SetFont('helvetica', '', 12);
        $this->SetFillColor(225);

        $this->Image($this->logo, 20, 20, 60);

        $this->addMetadataInformation($this->metadata, 20);
        $this->addCustomerInformation($this->customer, $this->GetY());
        $this->addPurchases($this->purchases, $this->GetY() + 20);
        $this->addTotalPurchases($this->total_purchases, $this->GetY() + 20);

        $this->Output('F', $filepath);
    }

    private function addMetadataInformation($infos, $y_position)
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

    private function addCustomerInformation($infos, $y_position)
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

    private function addPurchases($purchases, $y_position)
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
            $this->Cell(25, 5, $this->pdfDecode($purchase['number']), 0, 0);
            $this->Cell(25, 5, $this->pdfDecode($purchase['price']), 0, 0);
            $this->Cell(25, 5, $this->pdfDecode($purchase['total']), 0, 1);

            $this->SetXY(20, $this->GetY() + 10);
        }
    }

    private function addTotalPurchases($infos, $y_position)
    {
        $this->SetY($y_position);
        $this->SetFont('', 'B');

        $this->SetX(135);
        $this->Cell(25, 10, 'Prix HT', 0, 0, '', true);
        $this->Cell(25, 10, $this->pdfDecode($infos['ht']), 0, 1);

        $this->SetX(135);
        $this->Cell(25, 10, 'TVA', 0, 0, '', true);
        $this->Cell(25, 10, $this->pdfDecode($infos['tva']), 0, 1);

        $this->SetX(135);
        $this->Cell(25, 10, 'Total TTC', 0, 0, '', true);
        $this->Cell(25, 10, $this->pdfDecode($infos['ttc']), 0, 1);
    }

    // phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function Footer()
    {
        $offset = count($this->footer) * 5 + 20;
        $this->SetY(-$offset);
        $this->SetFont('', 'I', 10);
        foreach ($this->footer as $info) {
            $this->Cell(0, 5, $this->pdfDecode($info), 0, 1, 'C');
        }
    }

    private function pdfDecode($string)
    {
        return mb_convert_encoding($string, 'windows-1252', 'utf-8');
    }
}
