<?php

namespace Website\models;

use tests\factories\PaymentFactory;

class PaymentTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\TimeHelper;

    public function testComplete()
    {
        $completed_at = $this->fake('dateTime');
        $payment = PaymentFactory::create([
            'is_paid' => true,
            'completed_at' => null,
        ]);

        $payment->complete($completed_at);

        $this->assertEquals($completed_at, $payment->completed_at);
    }

    public function testCompleteSetsInvoiceNumber()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $payment = PaymentFactory::create([
            'is_paid' => true,
            'completed_at' => null,
            'invoice_number' => null,
        ]);

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0001';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    public function testCompleteIncrementsInvoiceNumberOverMonths()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);

        PaymentFactory::create([
            'invoice_number' => $now->format('Y') . '-01-0001',
        ]);
        PaymentFactory::create([
            'invoice_number' => $now->format('Y') . '-01-0002',
        ]);
        $payment = PaymentFactory::create([
            'is_paid' => true,
            'completed_at' => null,
            'invoice_number' => null,
        ]);

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0003';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    public function testCompleteResetsInvoiceNumberOverYears()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);

        $previous_year = \Minz\Time::ago(1, 'year');
        PaymentFactory::create([
            'invoice_number' => $previous_year->format('Y-m') . '-0001',
        ]);
        PaymentFactory::create([
            'invoice_number' => $previous_year->format('Y-m') . '-0002',
        ]);
        $payment = PaymentFactory::create([
            'is_paid' => true,
            'completed_at' => null,
            'invoice_number' => null,
        ]);

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0001';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    public function testCompleteIgnoresNullInvoiceNumbers()
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);

        PaymentFactory::create([
            'invoice_number' => null,
        ]);
        PaymentFactory::create([
            'invoice_number' => $now->format('Y') . '-01-0001',
        ]);
        $payment = PaymentFactory::create([
            'is_paid' => true,
            'completed_at' => null,
            'invoice_number' => null,
        ]);

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0002';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    public function testCompleteDoesNothingIfNotIsPaid()
    {
        $completed_at = $this->fake('dateTime');
        $payment = PaymentFactory::create([
            'is_paid' => false,
            'completed_at' => null,
        ]);

        $payment->complete($completed_at);

        $this->assertNull($payment->completed_at);
    }
}
