<?php

namespace Website\models\dao;

class PotUsageTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;

    public function testFindAvailableAmount()
    {
        $pot_usage_dao = new PotUsage();
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        $this->create('payment', [
            'type' => 'common_pot',
            'completed_at' => $this->fake('iso8601'),
            'amount' => $revenues,
        ]);
        $this->create('pot_usage', [
            'completed_at' => $this->fake('iso8601'),
            'amount' => $expenses,
        ]);

        $amount = $pot_usage_dao->findAvailableAmount();

        $expected_amount = $revenues - $expenses;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenNoExpenses()
    {
        $pot_usage_dao = new PotUsage();
        $revenues = $this->fake('numberBetween', 500, 1000);
        $this->create('payment', [
            'type' => 'common_pot',
            'completed_at' => $this->fake('iso8601'),
            'amount' => $revenues,
        ]);

        $amount = $pot_usage_dao->findAvailableAmount();

        $expected_amount = $revenues;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenRevenueIsNotCompleted()
    {
        $pot_usage_dao = new PotUsage();
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        $this->create('payment', [
            'type' => 'common_pot',
            'completed_at' => null,
            'amount' => $revenues,
        ]);
        $this->create('pot_usage', [
            'completed_at' => $this->fake('iso8601'),
            'amount' => $expenses,
        ]);

        $amount = $pot_usage_dao->findAvailableAmount();

        $expected_amount = -$expenses;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenRevenueIsSubscription()
    {
        $pot_usage_dao = new PotUsage();
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        $this->create('payment', [
            'type' => 'subscription',
            'completed_at' => $this->fake('iso8601'),
            'amount' => $revenues,
        ]);
        $this->create('pot_usage', [
            'completed_at' => $this->fake('iso8601'),
            'amount' => $expenses,
        ]);

        $amount = $pot_usage_dao->findAvailableAmount();

        $expected_amount = -$expenses;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenExpenseIsNotCompleted()
    {
        // Note this case should never happen in real life (pot_usages
        // are always completed)
        $pot_usage_dao = new PotUsage();
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        $this->create('payment', [
            'type' => 'common_pot',
            'completed_at' => $this->fake('iso8601'),
            'amount' => $revenues,
        ]);
        $this->create('pot_usage', [
            'completed_at' => null,
            'amount' => $expenses,
        ]);

        $amount = $pot_usage_dao->findAvailableAmount();

        $expected_amount = $revenues;
        $this->assertSame($expected_amount, $amount);
    }
}
