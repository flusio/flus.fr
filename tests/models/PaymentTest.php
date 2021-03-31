<?php

namespace Website\models;

class PaymentTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;

    public function testComplete()
    {
        $payment_dao = new dao\Payment();
        $completed_at = $this->fake('dateTime');
        $payment_id = $this->create('payment', [
            'is_paid' => 1,
            'completed_at' => null,
        ]);
        $payment = new Payment($payment_dao->find($payment_id));

        $payment->complete($completed_at);

        $this->assertEquals($completed_at, $payment->completed_at);
    }

    public function testCompleteSetsInvoiceNumber()
    {
        $payment_dao = new dao\Payment();
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $payment_id = $this->create('payment', [
            'is_paid' => 1,
            'completed_at' => null,
            'invoice_number' => null,
        ]);
        $payment = new Payment($payment_dao->find($payment_id));

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0001';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    public function testCompleteIncrementsInvoiceNumberOverMonths()
    {
        $payment_dao = new dao\Payment();
        $now = $this->fake('dateTime');
        $this->freeze($now);

        $this->create('payment', [
            'invoice_number' => $now->format('Y') . '-01-0001',
        ]);
        $this->create('payment', [
            'invoice_number' => $now->format('Y') . '-01-0002',
        ]);
        $payment_id = $this->create('payment', [
            'is_paid' => 1,
            'completed_at' => null,
            'invoice_number' => null,
        ]);
        $payment = new Payment($payment_dao->find($payment_id));

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0003';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    public function testCompleteResetsInvoiceNumberOverYears()
    {
        $payment_dao = new dao\Payment();
        $now = $this->fake('dateTime');
        $this->freeze($now);

        $previous_year = \Minz\Time::ago(1, 'year');
        $this->create('payment', [
            'invoice_number' => $previous_year->format('Y-m') . '-0001',
        ]);
        $this->create('payment', [
            'invoice_number' => $previous_year->format('Y-m') . '-0002',
        ]);
        $payment_id = $this->create('payment', [
            'is_paid' => 1,
            'completed_at' => null,
            'invoice_number' => null,
        ]);
        $payment = new Payment($payment_dao->find($payment_id));

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0001';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    public function testCompleteIgnoresNullInvoiceNumbers()
    {
        $payment_dao = new dao\Payment();
        $now = $this->fake('dateTime');
        $this->freeze($now);

        $this->create('payment', [
            'invoice_number' => null,
        ]);
        $this->create('payment', [
            'invoice_number' => $now->format('Y') . '-01-0001',
        ]);
        $payment_id = $this->create('payment', [
            'is_paid' => 1,
            'completed_at' => null,
            'invoice_number' => null,
        ]);
        $payment = new Payment($payment_dao->find($payment_id));

        $payment->complete($now);

        $expected_invoice_number = $now->format('Y-m') . '-0002';
        $this->assertSame($expected_invoice_number, $payment->invoice_number);
    }

    public function testCompleteDoesNothingIfNotIsPaid()
    {
        $payment_dao = new dao\Payment();
        $completed_at = $this->fake('dateTime');
        $payment_id = $this->create('payment', [
            'is_paid' => 0,
            'completed_at' => null,
        ]);
        $payment = new Payment($payment_dao->find($payment_id));

        $payment->complete($completed_at);

        $this->assertNull($payment->completed_at);
    }
}
