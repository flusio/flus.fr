<?php

namespace Website\services;

use PHPUnit\Framework\TestCase;
use Website\models;
use Website\utils;

class InvoicePDFTest extends TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\FactoriesHelper;

    public function testPdfHasALogo()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment');
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $this->assertStringEndsWith('.png', $invoice_pdf->logo);
        $this->assertTrue(file_exists($invoice_pdf->logo));
    }

    public function testPdfHasMetadata()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'completed_at' => $this->fake('dateTime')->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $expected_established = $date = strftime('%d %B %Y', $payment->created_at->getTimestamp());
        $expected_paid = $date = strftime('%d %B %Y', $payment->completed_at->getTimestamp());
        $this->assertSame($payment->invoice_number, $metadata['N° facture']);
        $this->assertSame($expected_established, $metadata['Établie le']);
        $this->assertSame($expected_paid, $metadata['Payée le']);
    }

    public function testPdfWithVatNumber()
    {
        $faker = \Faker\Factory::create('fr_FR');
        $vat_number = $faker->vat;
        $payment_dao = new models\dao\Payment();
        $account_id = $this->create('account', [
            'company_vat_number' => $vat_number,
        ]);
        $payment_id = $this->create('payment', [
            'account_id' => $account_id,
        ]);
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertSame($metadata['N° TVA client'], $vat_number);
    }

    public function testPdfNotCompletedIsDue()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'completed_at' => null,
        ]);
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertSame('à payer', $metadata['Payée le']);
    }

    public function testPdfToCredit()
    {
        $payment_dao = new models\dao\Payment();
        $credited_completed_at = $this->fake('dateTime');
        $random_number = sprintf('-%04d', $this->fake('randomNumber', 4));
        $credited_invoice_number = $credited_completed_at->format('Y-m') . $random_number;
        $credited_payment_id = $this->create('payment', [
            'invoice_number' => $credited_invoice_number,
        ]);
        $payment_id = $this->create('payment', [
            'type' => 'credit',
            'credited_payment_id' => $credited_payment_id,
            'completed_at' => $this->fake('dateTime')->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $payment = new models\Payment($payment_dao->find($payment_id));
        $expected_credited_at = strftime('%d %B %Y', $payment->completed_at->getTimestamp());

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertSame($expected_credited_at, $metadata['Créditée le']);
    }

    public function testPdfWithCommonPotPaymentHasNoId()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment');
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertArrayNotHasKey('Identifiant client', $metadata);
    }

    public function testPdfHasCustomer()
    {
        $account_id = $this->create('account', [
            'address_first_name' => $this->fake('firstName'),
            'address_last_name' => $this->fake('lastName'),
            'address_address1' => $this->fake('streetAddress'),
            'address_postcode' => $this->fake('postcode'),
            'address_city' => $this->fake('city'),
            'address_country' => $this->fake('randomElement', \Website\utils\Countries::codes()),
        ]);
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'account_id' => $account_id,
        ]);
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $address = $payment->account()->address();
        $expected_line1 = $address['first_name'] . ' ' . $address['last_name'];
        $expected_line2 = $address['address1'];
        $expected_line3 = $address['postcode'] . ' ' . $address['city'];
        $expected_line4 = utils\Countries::codeToLabel($address['country']);

        $this->assertSame($expected_line1, $invoice_pdf->customer[0]);
        $this->assertSame($expected_line2, $invoice_pdf->customer[1]);
        $this->assertSame($expected_line3, $invoice_pdf->customer[2]);
        $this->assertSame($expected_line4, $invoice_pdf->customer[3]);
    }

    public function testPdfWithMonthSubscriptionHasCorrespondingPurchase()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'type' => 'subscription',
            'frequency' => 'month',
        ]);
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $this->assertSame(1, count($invoice_pdf->purchases));
        $this->assertSame(
            "Renouvellement d'un abonnement\nde 1 mois à Flus",
            $invoice_pdf->purchases[0]['description']
        );
        $this->assertSame(
            1,
            $invoice_pdf->purchases[0]['number']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['price']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['total']
        );
    }

    public function testPdfWithYearSubscriptionHasCorrespondingPurchase()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'type' => 'subscription',
            'frequency' => 'year',
        ]);
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $this->assertSame(1, count($invoice_pdf->purchases));
        $this->assertSame(
            "Renouvellement d'un abonnement\nde 1 an à Flus",
            $invoice_pdf->purchases[0]['description']
        );
        $this->assertSame(
            1,
            $invoice_pdf->purchases[0]['number']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['price']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['total']
        );
    }

    public function testPdfWithCommonPotHasCorrespondingPurchase()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment', [
            'type' => 'common_pot',
        ]);
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $this->assertSame(1, count($invoice_pdf->purchases));
        $this->assertSame(
            "Participation à la cagnotte commune\nde Flus",
            $invoice_pdf->purchases[0]['description']
        );
        $this->assertSame(
            1,
            $invoice_pdf->purchases[0]['number']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['price']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['total']
        );
    }

    public function testPdfWithCreditHasCorrespondingPurchase()
    {
        $payment_dao = new models\dao\Payment();
        $credited_completed_at = $this->fake('dateTime');
        $random_number = sprintf('-%04d', $this->fake('randomNumber', 4));
        $credited_invoice_number = $credited_completed_at->format('Y-m') . $random_number;
        $credited_payment_id = $this->create('payment', [
            'invoice_number' => $credited_invoice_number,
        ]);
        $payment_id = $this->create('payment', [
            'type' => 'credit',
            'credited_payment_id' => $credited_payment_id,
        ]);
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $this->assertSame(1, count($invoice_pdf->purchases));
        $this->assertSame(
            "Remboursement de la facture\n{$credited_invoice_number}",
            $invoice_pdf->purchases[0]['description']
        );
        $this->assertSame(
            1,
            $invoice_pdf->purchases[0]['number']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['price']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['total']
        );
    }

    public function testPdfHasTotalPurchases()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment');
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->total_purchases['ht']
        );
        $this->assertSame(
            'non applicable',
            $invoice_pdf->total_purchases['tva']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->total_purchases['ttc']
        );
    }

    public function testPdfHasFooter()
    {
        $payment_dao = new models\dao\Payment();
        $payment_id = $this->create('payment');
        $payment = new models\Payment($payment_dao->find($payment_id));

        $invoice_pdf = new InvoicePDF($payment);

        $this->assertSame(
            'Marien Fressinaud Mas de Feix / Flus – 57 rue du Vercors, 38000 Grenoble – support@flus.io',
            $invoice_pdf->footer[0]
        );
        $this->assertSame(
            'micro-entreprise – N° Siret 878 196 278 00013 – 878 196 278 R.C.S. Grenoble',
            $invoice_pdf->footer[1]
        );
        $this->assertSame(
            'TVA non applicable, art. 293 B du CGI',
            $invoice_pdf->footer[2]
        );
    }
}
