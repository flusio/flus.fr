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

    /**
     * @dataProvider subscriptionPaymentProvider
     */
    public function testPdfHasALogo($payment)
    {
        $invoice_pdf = new InvoicePDF($payment);

        $this->assertStringEndsWith('.png', $invoice_pdf->logo);
        $this->assertTrue(file_exists($invoice_pdf->logo));
    }

    /**
     * @dataProvider subscriptionPaymentProvider
     */
    public function testPdfHasMetadata($payment)
    {
        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $expected_established = $date = strftime('%d %B %Y', $payment->created_at->getTimestamp());
        $expected_paid = $date = strftime('%d %B %Y', $payment->completed_at->getTimestamp());
        $this->assertSame($payment->invoice_number, $metadata['N° facture']);
        $this->assertSame($expected_established, $metadata['Établie le']);
        $this->assertSame($expected_paid, $metadata['Payée le']);
    }

    /**
     * @dataProvider subscriptionPaymentProvider
     */
    public function testPdfWithVatNumber($payment)
    {
        $faker = \Faker\Factory::create('fr_FR');
        $vat_number = $faker->vat;
        $payment->company_vat_number = $vat_number;

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertSame($metadata['N° TVA client'], $vat_number);
    }

    /**
     * @dataProvider subscriptionPaymentProvider
     */
    public function testPdfNotCompletedIsDue($payment)
    {
        $payment->completed_at = null;

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertSame('à payer', $metadata['Payée le']);
    }

    /**
     * @dataProvider creditPaymentProvider
     */
    public function testPdfToCredit($payment)
    {
        $credited_completed_at = $this->fake('dateTime');
        $random_number = sprintf('-%04d', $this->fake('randomNumber', 4));
        $credited_invoice_number = $credited_completed_at->format('Y-m') . $random_number;
        $credited_payment_id = $this->create('payment', [
            'amount' => $payment->amount,
            'invoice_number' => $credited_invoice_number,
        ]);
        $payment->credited_payment_id = $credited_payment_id;
        $expected_credited_at = strftime('%d %B %Y', $payment->completed_at->getTimestamp());

        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertSame($expected_credited_at, $metadata['Créditée le']);
    }

    /**
     * @dataProvider commonPotPaymentProvider
     */
    public function testPdfWithCommonPotPaymentHasNoId($payment)
    {
        $invoice_pdf = new InvoicePDF($payment);

        $metadata = $invoice_pdf->metadata;
        $this->assertArrayNotHasKey('Identifiant client', $metadata);
    }

    /**
     * @dataProvider subscriptionPaymentProvider
     */
    public function testPdfHasCustomer($payment)
    {
        $invoice_pdf = new InvoicePDF($payment);

        $address = $payment->address();
        $expected_line1 = $address['first_name'] . ' ' . $address['last_name'];
        $expected_line2 = $address['address1'];
        $expected_line3 = $address['postcode'] . ' ' . $address['city'];
        $expected_line4 = utils\Countries::codeToLabel($address['country']);

        $this->assertSame($expected_line1, $invoice_pdf->customer[0]);
        $this->assertSame($expected_line2, $invoice_pdf->customer[1]);
        $this->assertSame($expected_line3, $invoice_pdf->customer[2]);
        $this->assertSame($expected_line4, $invoice_pdf->customer[3]);
    }

    /**
     * @dataProvider subscriptionPaymentProvider
     */
    public function testPdfWithMonthSubscriptionHasCorrespondingPurchase($payment)
    {
        $payment->frequency = 'month';

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

    /**
     * @dataProvider subscriptionPaymentProvider
     */
    public function testPdfWithYearSubscriptionHasCorrespondingPurchase($payment)
    {
        $payment->frequency = 'year';

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

    /**
     * @dataProvider commonPotPaymentProvider
     */
    public function testPdfWithCommonPotHasCorrespondingPurchase($payment)
    {
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

    /**
     * @dataProvider creditPaymentProvider
     */
    public function testPdfWithCreditHasCorrespondingPurchase($payment)
    {
        $credited_completed_at = $this->fake('dateTime');
        $random_number = sprintf('-%04d', $this->fake('randomNumber', 4));
        $credited_invoice_number = $credited_completed_at->format('Y-m') . $random_number;
        $credited_payment_id = $this->create('payment', [
            'amount' => $payment->amount,
            'invoice_number' => $credited_invoice_number,
        ]);
        $payment->credited_payment_id = $credited_payment_id;

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

    /**
     * @dataProvider subscriptionPaymentProvider
     */
    public function testPdfHasTotalPurchases($payment)
    {
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

    /**
     * @dataProvider subscriptionPaymentProvider
     */
    public function testPdfHasFooter($payment)
    {
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

    public function subscriptionPaymentProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $completed_at = $faker->dateTime;
            $invoice_number = $completed_at->format('Y-m') . sprintf('-%04d', $faker->randomNumber(4));

            $payment = new models\Payment([
                'created_at' => $faker->dateTime->format(\Minz\Model::DATETIME_FORMAT),
                'type' => 'subscription',
                'frequency' => $faker->randomElement(['month', 'year']),
                'email' => $faker->email,
                'amount' => $faker->numberBetween(100, 100000),
                'address_first_name' => $faker->firstName,
                'address_last_name' => $faker->lastName,
                'address_address1' => $faker->streetAddress,
                'address_postcode' => $faker->postcode,
                'address_city' => $faker->city,
                'address_country' => $faker->randomElement(\Website\utils\Countries::codes()),
                'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
                'invoice_number' => $invoice_number,
            ]);

            $datasets[] = [$payment];
        }

        return $datasets;
    }

    public function commonPotPaymentProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $completed_at = $faker->dateTime;
            $invoice_number = $completed_at->format('Y-m') . sprintf('-%04d', $faker->randomNumber(4));

            $payment = new models\Payment([
                'created_at' => $faker->dateTime->format(\Minz\Model::DATETIME_FORMAT),
                'type' => 'common_pot',
                'email' => $faker->email,
                'amount' => $faker->numberBetween(100, 100000),
                'address_first_name' => $faker->firstName,
                'address_last_name' => $faker->lastName,
                'address_address1' => $faker->streetAddress,
                'address_postcode' => $faker->postcode,
                'address_city' => $faker->city,
                'address_country' => $faker->randomElement(\Website\utils\Countries::codes()),
                'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
                'invoice_number' => $invoice_number,
            ]);

            $datasets[] = [$payment];
        }

        return $datasets;
    }

    public function creditPaymentProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $completed_at = $faker->dateTime;
            $invoice_number = $completed_at->format('Y-m') . sprintf('-%04d', $faker->randomNumber(4));

            $payment = new models\Payment([
                'created_at' => $faker->dateTime->format(\Minz\Model::DATETIME_FORMAT),
                'type' => 'credit',
                'email' => $faker->email,
                'amount' => $faker->numberBetween(100, 100000),
                'address_first_name' => $faker->firstName,
                'address_last_name' => $faker->lastName,
                'address_address1' => $faker->streetAddress,
                'address_postcode' => $faker->postcode,
                'address_city' => $faker->city,
                'address_country' => $faker->randomElement(\Website\utils\Countries::codes()),
                'completed_at' => $completed_at->format(\Minz\Model::DATETIME_FORMAT),
                'invoice_number' => $invoice_number,
            ]);

            $datasets[] = [$payment];
        }

        return $datasets;
    }
}
