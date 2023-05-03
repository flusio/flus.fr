<?php

namespace Website\services;

use PHPUnit\Framework\TestCase;
use Minz\Output\ViewHelpers;
use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;
use Website\models;
use Website\utils;

class InvoicePDFTest extends TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;

    public function testPdfHasALogo()
    {
        $payment = PaymentFactory::create();

        $invoice_pdf = new InvoicePDF($payment);

        $this->assertStringEndsWith('.png', $invoice_pdf->logo);
        $this->assertTrue(file_exists($invoice_pdf->logo));
    }

    public function testPdfHasMetadata()
    {
        $payment = PaymentFactory::create([
            'completed_at' => $this->fake('dateTime'),
        ]);

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $expected_established = ViewHelpers::formatDate($payment->created_at, 'dd MMMM yyyy');
        $expected_paid = ViewHelpers::formatDate($payment->completed_at, 'dd MMMM yyyy');
        $this->assertSame($payment->invoice_number, $metadata['N° facture']);
        $this->assertSame($expected_established, $metadata['Établie le']);
        $this->assertSame($expected_paid, $metadata['Payée le']);
    }

    public function testPdfWithVatNumber()
    {
        $faker = \Faker\Factory::create('fr_FR');
        $vat_number = $faker->vat(); // @phpstan-ignore-line
        $account = AccountFactory::create([
            'company_vat_number' => $vat_number,
        ]);
        $payment = PaymentFactory::create([
            'account_id' => $account->id,
        ]);

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertSame($metadata['N° TVA client'], $vat_number);
    }

    public function testPdfNotCompletedIsDue()
    {
        $payment = PaymentFactory::create([
            'completed_at' => null,
        ]);

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertSame('à payer', $metadata['Payée le']);
    }

    public function testPdfToCredit()
    {
        $credited_completed_at = $this->fake('dateTime');
        $random_number = sprintf('-%04d', $this->fake('randomNumber', 4));
        $credited_invoice_number = $credited_completed_at->format('Y-m') . $random_number;
        $credited_payment = PaymentFactory::create([
            'invoice_number' => $credited_invoice_number,
        ]);
        $payment = PaymentFactory::create([
            'type' => 'credit',
            'credited_payment_id' => $credited_payment->id,
            'completed_at' => $this->fake('dateTime'),
        ]);
        $expected_credited_at = ViewHelpers::formatDate($payment->completed_at, 'dd MMMM yyyy');

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertSame($expected_credited_at, $metadata['Créditée le']);
    }

    public function testPdfWithCommonPotPaymentHasNoId()
    {
        $payment = PaymentFactory::create();

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertArrayNotHasKey('Identifiant client', $metadata);
    }

    public function testPdfHasCustomer()
    {
        $account = AccountFactory::create([
            'address_first_name' => $this->fake('firstName'),
            'address_last_name' => $this->fake('lastName'),
            'address_address1' => $this->fake('streetAddress'),
            'address_postcode' => $this->fake('postcode'),
            'address_city' => $this->fake('city'),
            'address_country' => $this->fake('randomElement', \Website\utils\Countries::codes()),
        ]);
        $payment = PaymentFactory::create([
            'account_id' => $account->id,
        ]);

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
        $payment = PaymentFactory::create([
            'type' => 'subscription',
            'frequency' => 'month',
        ]);

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
        $payment = PaymentFactory::create([
            'type' => 'subscription',
            'frequency' => 'year',
        ]);

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
        $payment = PaymentFactory::create([
            'type' => 'common_pot',
        ]);

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
        $credited_completed_at = $this->fake('dateTime');
        $random_number = sprintf('-%04d', $this->fake('randomNumber', 4));
        $credited_invoice_number = $credited_completed_at->format('Y-m') . $random_number;
        $credited_payment = PaymentFactory::create([
            'invoice_number' => $credited_invoice_number,
        ]);
        $payment = PaymentFactory::create([
            'type' => 'credit',
            'credited_payment_id' => $credited_payment->id,
        ]);

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
        $payment = PaymentFactory::create();

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
        $payment = PaymentFactory::create();

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
