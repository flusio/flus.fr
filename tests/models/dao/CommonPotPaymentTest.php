<?php

namespace Website\models\dao;

class CommonPotPaymentTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;

    public function testFindAvailableAmount()
    {
        $common_pot_payment_dao = new CommonPotPayment();
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        $this->create('payment', [
            'type' => 'common_pot',
            'completed_at' => $this->fake('iso8601'),
            'amount' => $revenues,
        ]);
        $this->create('common_pot_payment', [
            'completed_at' => $this->fake('iso8601'),
            'amount' => $expenses,
        ]);

        $amount = $common_pot_payment_dao->findAvailableAmount();

        $expected_amount = $revenues - $expenses;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenNoExpenses()
    {
        $common_pot_payment_dao = new CommonPotPayment();
        $revenues = $this->fake('numberBetween', 500, 1000);
        $this->create('payment', [
            'type' => 'common_pot',
            'completed_at' => $this->fake('iso8601'),
            'amount' => $revenues,
        ]);

        $amount = $common_pot_payment_dao->findAvailableAmount();

        $expected_amount = $revenues;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenRevenueIsNotCompleted()
    {
        $common_pot_payment_dao = new CommonPotPayment();
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        $this->create('payment', [
            'type' => 'common_pot',
            'completed_at' => null,
            'amount' => $revenues,
        ]);
        $this->create('common_pot_payment', [
            'completed_at' => $this->fake('iso8601'),
            'amount' => $expenses,
        ]);

        $amount = $common_pot_payment_dao->findAvailableAmount();

        $expected_amount = -$expenses;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenRevenueIsSubscription()
    {
        $common_pot_payment_dao = new CommonPotPayment();
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        $this->create('payment', [
            'type' => 'subscription',
            'completed_at' => $this->fake('iso8601'),
            'amount' => $revenues,
        ]);
        $this->create('common_pot_payment', [
            'completed_at' => $this->fake('iso8601'),
            'amount' => $expenses,
        ]);

        $amount = $common_pot_payment_dao->findAvailableAmount();

        $expected_amount = -$expenses;
        $this->assertSame($expected_amount, $amount);
    }

    public function testFindAvailableAmountWhenExpenseIsNotCompleted()
    {
        // Note this case should never happen in real life (common_pot_payments
        // are always completed)
        $common_pot_payment_dao = new CommonPotPayment();
        $revenues = $this->fake('numberBetween', 500, 1000);
        $expenses = $this->fake('numberBetween', 100, 499);
        $this->create('payment', [
            'type' => 'common_pot',
            'completed_at' => $this->fake('iso8601'),
            'amount' => $revenues,
        ]);
        $this->create('common_pot_payment', [
            'completed_at' => null,
            'amount' => $expenses,
        ]);

        $amount = $common_pot_payment_dao->findAvailableAmount();

        $expected_amount = $revenues;
        $this->assertSame($expected_amount, $amount);
    }
}
