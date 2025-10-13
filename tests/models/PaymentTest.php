<?php

namespace Website\models;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;

class PaymentTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\TimeHelper;

    public function testComplete(): void
    {
        $completed_at = $this->fake('dateTime');
        $payment = PaymentFactory::create([
            'is_paid' => true,
            'completed_at' => null,
        ]);

        $payment->complete($completed_at);

        $this->assertEquals($completed_at, $payment->completed_at);
    }

    public function testCompleteSetsInvoiceNumber(): void
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

    public function testCompleteIncrementsInvoiceNumberOverMonths(): void
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

    public function testCompleteResetsInvoiceNumberOverYears(): void
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

    public function testCompleteIgnoresNullInvoiceNumbers(): void
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

    public function testCompleteDoesNothingIfNotIsPaid(): void
    {
        $completed_at = $this->fake('dateTime');
        $payment = PaymentFactory::create([
            'is_paid' => false,
            'completed_at' => null,
        ]);

        $payment->complete($completed_at);

        $this->assertNull($payment->completed_at);
    }

    public function testContributionPrice(): void
    {
        \Minz\Configuration::$application['financial_goal'] = 200;
        $account_1 = AccountFactory::create([
            'expired_at' => \Minz\Time::fromNow(1, 'month'),
        ]);
        $account_2 = AccountFactory::create([
            'expired_at' => \Minz\Time::fromNow(1, 'month'),
        ]);
        PaymentFactory::create([
            'account_id' => $account_1->id,
        ]);
        PaymentFactory::create([
            'account_id' => $account_2->id,
        ]);

        $contribution_price = Payment::contributionPrice();

        $this->assertSame(67, $contribution_price);
    }

    public function testContributionPriceExcludesExpiredAccounts(): void
    {
        \Minz\Configuration::$application['financial_goal'] = 200;
        $account_1 = AccountFactory::create([
            'expired_at' => \Minz\Time::fromNow(1, 'month'),
        ]);
        $account_2 = AccountFactory::create([
            'expired_at' => \Minz\Time::ago(1, 'month'),
        ]);
        PaymentFactory::create([
            'account_id' => $account_1->id,
        ]);
        PaymentFactory::create([
            'account_id' => $account_2->id,
        ]);

        $contribution_price = Payment::contributionPrice();

        $this->assertSame(100, $contribution_price);
    }

    public function testContributionPriceExcludesFreeMonthAccounts(): void
    {
        \Minz\Configuration::$application['financial_goal'] = 200;
        $account_1 = AccountFactory::create([
            'expired_at' => \Minz\Time::fromNow(1, 'month'),
        ]);
        $account_2 = AccountFactory::create([
            'expired_at' => \Minz\Time::fromNow(1, 'month'),
        ]);
        PaymentFactory::create([
            'account_id' => $account_1->id,
        ]);

        $contribution_price = Payment::contributionPrice();

        $this->assertSame(100, $contribution_price);
    }

    public function testContributionPriceExcludesFreeAccounts(): void
    {
        \Minz\Configuration::$application['financial_goal'] = 200;
        $account_1 = AccountFactory::create([
            'expired_at' => \Minz\Time::fromNow(1, 'month'),
        ]);
        $account_2 = AccountFactory::create([
            'expired_at' => \Minz\Time::relative('@0'),
        ]);
        PaymentFactory::create([
            'account_id' => $account_1->id,
        ]);
        PaymentFactory::create([
            'account_id' => $account_2->id,
        ]);

        $contribution_price = Payment::contributionPrice();

        $this->assertSame(100, $contribution_price);
    }

    public function testContributionPriceIsMax120(): void
    {
        \Minz\Configuration::$application['financial_goal'] = 200;

        $contribution_price = Payment::contributionPrice();

        $this->assertSame(120, $contribution_price);
    }
}
