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
        $common_pot_revenues = $this->fake('numberBetween', 500, 100000);
        $subscriptions_revenues = $this->fake('numberBetween', 100, 100000);
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
        $this->assertResponsePointer($response, 'common_pots/show.phtml');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testContributionRendersCorrectly(array $address): void
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/common-pot/contribute');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'common_pots/contribution.phtml');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testContributionRendersIfOngoingPayment(array $address): void
    {
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        PaymentFactory::create([
            'account_id' => $user['account_id'],
            'completed_at' => null,
        ]);

        $response = $this->appRun('GET', '/account/common-pot/contribute');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Attention, vous avez un paiement en cours de traitement');
    }


    public function testContributionRedirectsIfNoAddress(): void
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/common-pot/contribute');

        $this->assertResponseCode($response, 302, '/account/address');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testContributionFailsIfNotConnected(array $address): void
    {
        AccountFactory::create([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/common-pot/contribute');

        $this->assertResponseCode($response, 401);
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeRedirectsCorrectly(int $amount, array $address): void
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => \Minz\Csrf::generate(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $payment = models\Payment::take();
        $this->assertNotNull($payment);
        $redirect_to = "/payments/{$payment->id}/pay";
        $this->assertResponseCode($response, 302, $redirect_to);
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeAcceptsFloatAmounts(int $amount, array $address): void
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $amount = $this->fake('randomFloat', 2, 1.00, 1000.0);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => \Minz\Csrf::generate(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 302);
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeCreatesAPayment(int $amount, array $address): void
    {
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $this->assertSame(0, models\Payment::count());

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => \Minz\Csrf::generate(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertSame(1, models\Payment::count());

        $payment = models\Payment::take();
        $this->assertNotNull($payment);
        $this->assertSame('common_pot', $payment->type);
        $this->assertSame($amount * 100, $payment->amount);
        $this->assertNull($payment->completed_at);
        $this->assertNotNull($payment->payment_intent_id);
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeRedirectsIfNoAddress(int $amount, array $address): void
    {
        $this->loginUser();

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => \Minz\Csrf::generate(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 302, '/account/address');
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeFailsIfNotConnected(int $amount, array $address): void
    {
        AccountFactory::create([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => \Minz\Csrf::generate(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 401);
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeWithInvalidCsrfReturnsABadRequest(int $amount, array $address): void
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => 'not the token',
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeWithoutAcceptingCgvReturnsABadRequest(int $amount, array $address): void
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => \Minz\Csrf::generate(),
            'amount' => $amount,
            'accept_cgv' => false,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Vous devez accepter ces conditions pour participer à la cagnotte.');
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeWithAmountLessThan1ReturnsABadRequest(int $amount, array $address): void
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $amount = $this->fake('randomFloat', 2, 0.0, 0.99);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => \Minz\Csrf::generate(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Le montant doit être compris entre 1 et 1000 €.');
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeWithAmountMoreThan1000ReturnsABadRequest(int $amount, array $address): void
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $amount = $this->fake('numberBetween', 1001, PHP_INT_MAX / 100);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => \Minz\Csrf::generate(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Le montant doit être compris entre 1 et 1000 €.');
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeWithAmountAsStringReturnsABadRequest(int $amount, array $address): void
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $amount = $this->fake('word');

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => \Minz\Csrf::generate(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Le montant doit être compris entre 1 et 1000 €.');
    }

    /**
     * @dataProvider contributeProvider
     *
     * @param AccountAddress $address
     */
    public function testContributeWithMissingAmountReturnsABadRequest(int $amount, array $address): void
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => \Minz\Csrf::generate(),
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Le montant doit être compris entre 1 et 1000 €.');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUsageRendersCorrectly(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 0, 7), 'days');
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Vous êtes sur le point de renouveler un mois');
        $this->assertResponsePointer($response, 'common_pots/usage.phtml');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUsageRendersIfCommonPotIsNotFullEnough(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 0, 7), 'days');
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);
        PotUsageFactory::create([
            'amount' => 300,
        ]);

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'il n’y a plus assez d’argent dans la cagnotte');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUsageRendersIfFreeAccount(array $address): void
    {
        $expired_at = new \DateTimeImmutable('@0');
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Vous bénéficiez déjà d’un compte gratuit');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUsageRendersNotExpiringSoon(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 8, 42), 'days');
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains(
            $response,
            'Vous pourrez utiliser la cagnotte lorsque votre abonnement sera sur le point d’expirer'
        );
    }

    public function testUsageRedirectsIfNoAddress(): void
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponseCode($response, 302, '/account/address');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUsageFailsIfNotConnected(array $address): void
    {
        AccountFactory::create([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponseCode($response, 401);
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUseExtendsSubscriptionAndRedirectsCorrectly(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 0, 7), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $this->assertSame(0, models\PotUsage::count());

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => \Minz\Csrf::generate(),
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $this->assertSame(1, models\PotUsage::count());
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertGreaterThan($expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }

    public function testUseRedirectsIfNoAddress(): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 0, 7), 'days');
        $user = $this->loginUser([
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => \Minz\Csrf::generate(),
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 302, '/account/address');
        $this->assertSame(0, models\PotUsage::count());
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUseFailsIfNotConnected(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 0, 7), 'days');
        $account = AccountFactory::create([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => \Minz\Csrf::generate(),
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 401);
        $this->assertSame(0, models\PotUsage::count());
        $account = $account->reload();
        $this->assertNotNull($account);
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUseFailsIfAcceptCgvIsFalse(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 0, 7), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => \Minz\Csrf::generate(),
            'accept_cgv' => false,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Vous devez accepter ces conditions');
        $this->assertSame(0, models\PotUsage::count());
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUseFailsIfCsrfIsInvalid(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 0, 7), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => 'not the token',
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
        $this->assertSame(0, models\PotUsage::count());
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUseFailsIfCommonPotIsNotFullEnough(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 0, 7), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 200,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => \Minz\Csrf::generate(),
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'La cagnotte n’est pas suffisamment fournie');
        $this->assertSame(0, models\PotUsage::count());
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUseFailsIfFreeAccount(array $address): void
    {
        $expired_at = new \DateTimeImmutable('@0');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => \Minz\Csrf::generate(),
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre abonnement n’est pas encore prêt d’expirer');
        $this->assertSame(0, models\PotUsage::count());
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testUseFailsIfNotExpiringSoon(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 8, 42), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => \Minz\Csrf::generate(),
            'accept_cgv' => true,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre abonnement n’est pas encore prêt d’expirer');
        $this->assertSame(0, models\PotUsage::count());
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @return array<array{AccountAddress}>
     */
    public function addressProvider(): array
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'address1' => $faker->streetAddress,
                    'postcode' => $faker->postcode,
                    'city' => $faker->city,
                    'country' => $faker->randomElement(utils\Countries::codes()),
                ],
            ];
        }

        return $datasets;
    }

    /**
     * @return array<array{int, AccountAddress}>
     */
    public function contributeProvider(): array
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                $faker->numberBetween(1, 1000),
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'address1' => $faker->streetAddress,
                    'postcode' => $faker->postcode,
                    'city' => $faker->city,
                    'country' => $faker->randomElement(utils\Countries::codes()),
                ],
            ];
        }

        return $datasets;
    }
}
