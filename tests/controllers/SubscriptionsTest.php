<?php

namespace Website\controllers;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;
use Website\models;
use Website\utils;

/**
 * @phpstan-import-type AccountAddress from models\Account
 */
class SubscriptionsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\TimeHelper;

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testInitRendersCorrectly(array $address): void
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'subscriptions/init.phtml');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testInitShowsInfoIfNotExpired(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('randomDigitNotNull'), 'days');
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre abonnement expirera le');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testInitRendersIfOngoingPayment(array $address): void
    {
        $expired_at = \Minz\Time::fromNow($this->fake('randomDigitNotNull'), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at,
        ]);
        PaymentFactory::create([
            'account_id' => $user['account_id'],
            'completed_at' => null,
        ]);

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Attention, vous avez un paiement en cours de traitement');
    }

    public function testInitRedirectsIfNoAddress(): void
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponseCode($response, 302, '/account/profile');
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testInitFailsIfNotConnected(array $address): void
    {
        AccountFactory::create([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/renew');

        $this->assertResponseCode($response, 401);
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testRenewCreatesAPaymentAndRedirects(array $address): void
    {
        $expired_at = \Minz\Time::fromNow(15, 'days');
        $user = $this->loginUser([
            'expired_at' => $expired_at,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $account = models\Account::find($user['account_id']);

        $this->assertSame(0, models\Payment::count());
        $this->assertNotNull($account);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'right_of_withdrawal' => true,
            'account_id' => $account->id,
            'amount' => 10,
        ]);

        $this->assertSame(1, models\Payment::count());

        $payment = models\Payment::take();
        $this->assertNotNull($payment);
        $this->assertResponseCode($response, 302, "/payments/{$payment->id}/pay");
        $this->assertNull($payment->completed_at);
        $this->assertSame(1000, $payment->amount);
        $this->assertSame('year', $payment->frequency);
        $this->assertSame($account->id, $payment->account_id);
        $this->assertNotNull($payment->payment_intent_id);
        $this->assertNotNull($payment->session_id);
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testRenewConsidersManagedAccounts(array $address): void
    {
        $expired_at = \Minz\Time::fromNow(15, 'days');
        $user = $this->loginUser([
            'expired_at' => $expired_at,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        AccountFactory::create(['managed_by_id' => $account->id]);
        AccountFactory::create(['managed_by_id' => $account->id]);

        $this->assertSame(0, models\Payment::count());

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'right_of_withdrawal' => true,
            'account_id' => $account->id,
            'amount' => 10,
        ]);

        $this->assertSame(1, models\Payment::count());

        $payment = models\Payment::take();
        $this->assertNotNull($payment);
        $this->assertResponseCode($response, 302, "/payments/{$payment->id}/pay");
        $this->assertSame(1000, $payment->amount);
        $this->assertSame(3, $payment->quantity);
        $this->assertSame(3000, $payment->totalAmount());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testRenewCreatesAcceptsAmountOfZero(array $address): void
    {
        $this->freeze();
        $expired_at = \Minz\Time::fromNow(15, 'days');
        $expected_expired_at = \Minz\Time::relative('1 year 15 days');
        $user = $this->loginUser([
            'expired_at' => $expired_at,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $account = models\Account::find($user['account_id']);

        $this->assertSame(0, models\Payment::count());
        $this->assertNotNull($account);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'right_of_withdrawal' => true,
            'account_id' => $account->id,
            'amount' => 0,
        ]);

        $this->assertSame(0, models\Payment::count());
        $this->assertResponseCode($response, 302, '/merci');
        $account = $account->reload();
        $this->assertSame(
            $expected_expired_at->getTimestamp(),
            $account->expired_at->getTimestamp()
        );
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testRenewRedirectsIfNoAddress(array $address): void
    {
        $expired_at = \Minz\Time::fromNow(15, 'days');
        $user = $this->loginUser([
            'expired_at' => $expired_at,
        ]);
        $account = models\Account::find($user['account_id']);

        $this->assertNotNull($account);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'right_of_withdrawal' => true,
            'account_id' => $account->id,
            'amount' => 10,
        ]);

        $this->assertResponseCode($response, 302, '/account/profile');
        $this->assertSame(0, models\Payment::count());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testRenewFailsIfExpiresInMoreThanOneMonth(array $address): void
    {
        $expired_at = \Minz\Time::fromNow(2, 'months');
        $user = $this->loginUser([
            'expired_at' => $expired_at,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $account = models\Account::find($user['account_id']);

        $this->assertNotNull($account);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'right_of_withdrawal' => true,
            'account_id' => $account->id,
            'amount' => 10,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains(
            $response,
            'Vous pourrez renouveler à 1 mois de l’expiration de votre abonnement.'
        );
        $this->assertSame(0, models\Payment::count());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testRenewFailsIfAmountIsInvalid(array $address): void
    {
        $expired_at = \Minz\Time::fromNow(15, 'days');
        $user = $this->loginUser([
            'expired_at' => $expired_at,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $account = models\Account::find($user['account_id']);

        $this->assertNotNull($account);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'right_of_withdrawal' => true,
            'account_id' => $account->id,
            'amount' => 121,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Le montant doit être compris entre 1 et 120 €.');
        $this->assertSame(0, models\Payment::count());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testRenewFailsIfNotConnected(array $address): void
    {
        $expired_at = \Minz\Time::fromNow(15, 'days');
        $account = AccountFactory::create([
            'expired_at' => $expired_at,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => \Minz\Csrf::generate(),
            'right_of_withdrawal' => true,
            'account_id' => $account->id,
            'amount' => 10,
        ]);

        $this->assertResponseCode($response, 401);
        $this->assertSame(0, models\Payment::count());
    }

    /**
     * @dataProvider addressProvider
     *
     * @param AccountAddress $address
     */
    public function testRenewFailsIfCsrfIsInvalid(array $address): void
    {
        $expired_at = \Minz\Time::fromNow(15, 'days');
        $user = $this->loginUser([
            'expired_at' => $expired_at,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $account = models\Account::find($user['account_id']);

        $this->assertNotNull($account);

        $response = $this->appRun('POST', '/account/renew', [
            'csrf' => 'not the token',
            'right_of_withdrawal' => true,
            'account_id' => $account->id,
            'amount' => 10,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
        $this->assertSame(0, models\Payment::count());
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
                    'legal_name' => '',
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
