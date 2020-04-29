<?php

namespace Website\services;

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

        $this->logo = \Minz\Configuration::$app_path . '/public/static/logo-512px.png';

        $established_at = strftime('%d %B %Y', $payment->created_at->getTimestamp());
        if ($payment->completed_at) {
            $paid_at = strftime('%d %B %Y', $payment->completed_at->getTimestamp());
        } else {
            $paid_at = 'à payer';
        }
        $this->metadata = [
            'N° facture' => $payment->invoice_number,
            'Établie le' => $established_at,
            'Payée le' => $paid_at,
        ];

        $address = $payment->address();
        $this->customer = [
            $address['first_name'] . ' ' . $address['last_name'],
            $address['address1'],
            $address['postcode'] . ' ' . $address['city'],
            utils\Countries::codeToLabel($address['country']),
        ];

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
        } else {
            $this->metadata['Identifiant client'] = $payment->username;

            $period = $payment->frequency === 'month' ? '1 mois' : '1 an';
            $this->purchases = [
                [
                    'description' => "Renouvellement d'un abonnement\nde " . $period . " à Flus",
                    'number' => 1,
                    'price' => $amount,
                    'total' => $amount,
                ],
            ];
        }

        if ($payment->company_vat_number) {
            $this->metadata['N° TVA client'] = $payment->company_vat_number;
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

        $this->addMetadataInformation($this->metadata);
        $this->addCustomerInformation($this->customer);
        $this->addPurchases($this->purchases);
        $this->addTotalPurchases($this->total_purchases);

        $this->Output('F', $filepath);
    }

    private function addMetadataInformation($infos)
    {
        $this->SetY(20);
        foreach ($infos as $info_key => $info_value) {
            $this->SetX(-100);
            $this->SetFont('', '');
            $this->Cell(40, 10, $this->pdfDecode($info_key), 0, 0);
            $this->SetFont('', 'B');
            $this->Cell(0, 10, $this->pdfDecode($info_value), 0, 1);
        }
    }

    private function addCustomerInformation($infos)
    {
        $this->SetY(70);
        $this->SetX(-100);

        $this->SetFont('', '');
        $this->Cell(0, 10, 'Adresse client', 0, 1);

        $this->SetFont('', 'B');
        foreach ($infos as $info) {
            $this->SetX(-100);
            $this->Cell(0, 5, $this->pdfDecode($info), 0, 1);
        }
    }

    private function addPurchases($purchases)
    {
        $this->SetXY(20, 130);
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

    private function addTotalPurchases($infos)
    {
        $this->SetY($this->GetY() + 20);
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
