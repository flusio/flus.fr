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

    public function testPdfHasALogo(): void
    {
        $payment = PaymentFactory::create();

        $invoice_pdf = new InvoicePDF($payment);

        $this->assertStringEndsWith('.png', $invoice_pdf->logo);
        $this->assertTrue(file_exists($invoice_pdf->logo));
    }

    public function testPdfHasGlobalInfo(): void
    {
        $payment = PaymentFactory::create([
            'is_paid' => true,
        ]);
        $payment->complete(\Minz\Time::now());
        $payment->save();

        $invoice_pdf = new InvoicePDF($payment);

        $global_info = $invoice_pdf->global_info;
        $expected_established = ViewHelpers::formatDate($payment->created_at, 'dd MMMM yyyy');
        assert($payment->completed_at !== null);
        $expected_paid = ViewHelpers::formatDate($payment->completed_at, 'dd MMMM yyyy');
        $this->assertSame($payment->invoice_number, $global_info['N° facture']);
        $this->assertSame($expected_established, $global_info['Établie le']);
        $this->assertSame($expected_paid, $global_info['Payée le']);
    }

    public function testPdfNotCompletedIsDue(): void
    {
        $payment = PaymentFactory::create([
            'completed_at' => null,
        ]);

        $invoice_pdf = new InvoicePDF($payment);

        $global_info = $invoice_pdf->global_info;
        $this->assertSame('à payer', $global_info['Payée le']);
    }

    public function testPdfToCredit(): void
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
        assert($payment->completed_at !== null);
        $expected_credited_at = ViewHelpers::formatDate($payment->completed_at, 'dd MMMM yyyy');

        $invoice_pdf = new InvoicePDF($payment);

        $global_info = $invoice_pdf->global_info;
        $this->assertSame($expected_credited_at, $global_info['Créditée le']);
    }

    public function testPdfWithCommonPotPaymentHasNoId(): void
    {
        $payment = PaymentFactory::create();

        $invoice_pdf = new InvoicePDF($payment);

        $global_info = $invoice_pdf->global_info;
        $this->assertArrayNotHasKey('Identifiant client', $global_info);
    }

    public function testPdfHasCustomer(): void
    {
        $account = AccountFactory::create([
            'entity_type' => 'natural',
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

        $account = $payment->account();
        assert($account !== null);
        $address = $account->address();
        $expected_line1 = $address['first_name'] . ' ' . $address['last_name'];
        $expected_line2 = $address['address1'];
        $expected_line3 = $address['postcode'] . ' ' . $address['city'];
        $expected_line4 = utils\Countries::codeToLabel($address['country']);

        $this->assertSame($expected_line1, $invoice_pdf->customer[0]);
        $this->assertSame($expected_line2, $invoice_pdf->customer[1]);
        $this->assertSame($expected_line3, $invoice_pdf->customer[2]);
        $this->assertSame($expected_line4, $invoice_pdf->customer[3]);
    }

    public function testPdfForLegalEntity(): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $legal_name = $faker->company();
        $vat_number = $faker->vat(); // @phpstan-ignore-line
        $account = AccountFactory::create([
            'entity_type' => 'legal',
            'address_legal_name' => $legal_name,
            'company_vat_number' => $vat_number,
        ]);
        $payment = PaymentFactory::create([
            'account_id' => $account->id,
        ]);

        $invoice_pdf = new InvoicePDF($payment);

        $global_info = $invoice_pdf->global_info;
        $this->assertSame($global_info['N° TVA client'], $vat_number);
        $this->assertSame($legal_name, $invoice_pdf->customer[0]);
    }

    public function testPdfWithYearSubscriptionHasCorrespondingPurchase(): void
    {
        $payment = PaymentFactory::create([
            'type' => 'subscription',
            'frequency' => 'year',
            'quantity' => 3,
        ]);

        $invoice_pdf = new InvoicePDF($payment);

        $this->assertSame(1, count($invoice_pdf->purchases));
        $this->assertSame(
            "Renouvellement d'un abonnement\nde 1 an à Flus",
            $invoice_pdf->purchases[0]['description']
        );
        $this->assertSame(
            (string) $payment->quantity,
            $invoice_pdf->purchases[0]['quantity']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['price']
        );
        $this->assertSame(
            ($payment->totalAmount() / 100) . ' €',
            $invoice_pdf->purchases[0]['total']
        );
    }

    public function testPdfWithCommonPotHasCorrespondingPurchase(): void
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
            (string) $payment->quantity,
            $invoice_pdf->purchases[0]['quantity']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['price']
        );
        $this->assertSame(
            ($payment->totalAmount() / 100) . ' €',
            $invoice_pdf->purchases[0]['total']
        );
    }

    public function testPdfWithCreditHasCorrespondingPurchase(): void
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
            (string) $payment->quantity,
            $invoice_pdf->purchases[0]['quantity']
        );
        $this->assertSame(
            ($payment->amount / 100) . ' €',
            $invoice_pdf->purchases[0]['price']
        );
        $this->assertSame(
            ($payment->totalAmount() / 100) . ' €',
            $invoice_pdf->purchases[0]['total']
        );
    }

    public function testPdfHasTotalPurchases(): void
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
}
