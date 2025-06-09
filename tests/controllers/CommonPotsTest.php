<?php

namespace Website\controllers;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;
use tests\factories\PotUsageFactory;
use Website\models;
use Website\utils;

/**
 * @phpstan-import-type AccountAddress from models\Account
 */
class CommonPotsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testShowPublicRendersCorrectly(): void
    {
        $common_pot_expenses = $this->fake('numberBetween', 100, 499);
        $common_pot_revenues = $this->fake('numberBetween', 500, 12000);
        $subscriptions_revenues = $this->fake('numberBetween', 100, 12000);
        PotUsageFactory::create([
            'amount' => $common_pot_expenses,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => $common_pot_revenues,
            'completed_at' => $this->fake('dateTime'),
        ]);
        PaymentFactory::create([
            'type' => 'subscription',
            'amount' => $subscriptions_revenues,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('GET', '/cagnotte');

        $expected_amount = ($common_pot_revenues - $common_pot_expenses) / 100;
        $expected_formatted_amount = number_format($expected_amount, 2, ',', '&nbsp') . '&nbsp;€';
        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, $expected_formatted_amount);
        $this->assertResponseTemplateName($response, 'common_pots/show.phtml');
    }
}
