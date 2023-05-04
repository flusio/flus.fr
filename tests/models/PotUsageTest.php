<?php

namespace Website\models;

use tests\factories\PaymentFactory;
use tests\factories\PotUsageFactory;

class PotUsageTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\TimeHelper;

    public function testFindAvailableAmount(): void
    {
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        PaymentFactory::create([
            'type' => 'common_pot',
            'completed_at' => $this->fake('dateTime'),
            'amount' => $revenues,
        ]);
        PotUsageFactory::create([
            'completed_at' => $this->fake('dateTime'),
            'amount' => $expenses,
        ]);

        $amount = PotUsage::findAvailableAmount();

        $expected_amount = $revenues - $expenses;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenNoExpenses(): void
    {
        $revenues = $this->fake('numberBetween', 500, 1000);
        PaymentFactory::create([
            'type' => 'common_pot',
            'completed_at' => $this->fake('dateTime'),
            'amount' => $revenues,
        ]);

        $amount = PotUsage::findAvailableAmount();

        $expected_amount = $revenues;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenRevenueIsNotCompleted(): void
    {
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        PaymentFactory::create([
            'type' => 'common_pot',
            'completed_at' => null,
            'amount' => $revenues,
        ]);
        PotUsageFactory::create([
            'completed_at' => $this->fake('dateTime'),
            'amount' => $expenses,
        ]);

        $amount = PotUsage::findAvailableAmount();

        $expected_amount = -$expenses;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenRevenueIsSubscription(): void
    {
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        PaymentFactory::create([
            'type' => 'subscription',
            'completed_at' => $this->fake('dateTime'),
            'amount' => $revenues,
        ]);
        PotUsageFactory::create([
            'completed_at' => $this->fake('dateTime'),
            'amount' => $expenses,
        ]);

        $amount = PotUsage::findAvailableAmount();

        $expected_amount = -$expenses;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenExpenseIsNotCompleted(): void
    {
        // Note this case should never happen in real life (pot_usages
        // are always completed)
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        PaymentFactory::create([
            'type' => 'common_pot',
            'completed_at' => $this->fake('dateTime'),
            'amount' => $revenues,
        ]);
        PotUsageFactory::create([
            'completed_at' => null,
            'amount' => $expenses,
        ]);

        $amount = PotUsage::findAvailableAmount();

        $expected_amount = $revenues;
        $this->assertSame($expected_amount, $amount);
    }
}
