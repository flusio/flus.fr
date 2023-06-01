<?php

namespace Website\controllers;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;
use tests\factories\TokenFactory;
use Website\models;
use Website\utils;

/**
 * @phpstan-import-type AccountAddress from models\Account
 */
class AccountsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\TimeHelper;

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testShowRendersCorrectly(string $email, array $address): void
    {
        $this->loginUser([
            'email' => $email,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, $email);
        $this->assertResponsePointer($response, 'accounts/show.phtml');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testShowRendersFutureExpiration(string $email, array $address): void
    {
        $this->freeze($this->fake('dateTime'));
        $expired_at = \Minz\Time::fromNow($this->fake('randomDigitNotNull'), 'days');
        $this->loginUser([
            'expired_at' => $expired_at,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre abonnement expirera le');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testShowRendersPriorExpiration(string $email, array $address): void
    {
        $this->freeze($this->fake('dateTime'));
        $expired_at = \Minz\Time::ago($this->fake('randomDigitNotNull'), 'days');
        $this->loginUser([
            'expired_at' => $expired_at,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre abonnement a expiré le');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testShowRendersIfNoExpiration(string $email, array $address): void
    {
        $expired_at = new \DateTimeImmutable('@0');
        $this->loginUser([
            'expired_at' => $expired_at,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Vous bénéficiez d’un abonnement gratuit');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testShowRendersIfOngoingAndUnpaidPayment(string $email, array $address): void
    {
        $user = $this->loginUser([
            'email' => $email,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        PaymentFactory::create([
            'account_id' => $user['account_id'],
            'completed_at' => null,
            'is_paid' => false,
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre paiement est en cours de traitement');
        $this->assertResponsePointer($response, 'accounts/show.phtml');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testShowCompletesAnOngoingAndPaidPayment(string $email, array $address): void
    {
        $user = $this->loginUser([
            'email' => $email,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $payment = PaymentFactory::create([
            'account_id' => $user['account_id'],
            'completed_at' => null,
            'is_paid' => true,
            'frequency' => 'year',
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponseNotContains($response, 'Votre paiement est en cours de traitement');
        $payment = $payment->reload();
        $this->assertNotNull($payment);
        $this->assertNotNull($payment->completed_at);
    }

    public function testShowRedirectsIfNoAddress(): void
    {
        $email = $this->fake('email');
        $this->loginUser([
            'email' => $email,
            'address_first_name' => null,
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponseCode($response, 302, '/account/profile');
    }

    public function testShowFailsIfNotConnected(): void
    {
        $response = $this->appRun('GET', '/account');

        $this->assertResponseCode($response, 401);
        $this->assertResponseContains($response, 'Désolé, mais vous n’êtes pas connecté‧e');
    }

    public function testLoginRedirectsToShow(): void
    {
        $token = TokenFactory::create([
            'expired_at' => \Minz\Time::fromNow(30, 'days'),
        ]);
        $account = AccountFactory::create([
            'access_token' => $token->token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account->id,
            'access_token' => $token->token,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $user = utils\CurrentUser::get();
        $this->assertNotNull($user);
        $this->assertSame($account->id, $user['account_id']);
    }

    public function testLoginDeletesTheAccessToken(): void
    {
        $token = TokenFactory::create([
            'expired_at' => \Minz\Time::fromNow(30, 'days'),
        ]);
        $account = AccountFactory::create([
            'access_token' => $token->token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account->id,
            'access_token' => $token->token,
        ]);

        $account = $account->reload();
        $this->assertNotNull($account);
        $this->assertNull($account->access_token);
        $this->assertFalse(models\Token::exists($token->token));
    }

    public function testLoginRedirectsIfAlreadyConnected(): void
    {
        $token = TokenFactory::create([
            'expired_at' => \Minz\Time::fromNow(30, 'days'),
        ]);
        $user = $this->loginUser([
            'access_token' => $token->token,
        ]);
        $account_id = $user['account_id'];

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account_id,
            'access_token' => $token->token,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $user = utils\CurrentUser::get();
        $this->assertNotNull($user);
        $this->assertSame($account_id, $user['account_id']);
    }

    public function testLoginRedirectsIfAlreadyConnectedAsAdmin(): void
    {
        $user = $this->loginAdmin();
        $token = TokenFactory::create([
            'expired_at' => \Minz\Time::fromNow(30, 'days'),
        ]);
        $account = AccountFactory::create([
            'access_token' => $token->token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account->id,
            'access_token' => $token->token,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $user = utils\CurrentUser::get();
        $this->assertNotNull($user);
        $this->assertSame($account->id, $user['account_id']);
    }

    public function testLoginFailsIfAccountIdIsInvalid(): void
    {
        $token = TokenFactory::create([
            'expired_at' => \Minz\Time::fromNow(30, 'days'),
        ]);
        $account = AccountFactory::create([
            'access_token' => $token->token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => 'not the id',
            'access_token' => $token->token,
        ]);

        $this->assertResponseCode($response, 404);
        $user = utils\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testLoginFailsIfAccessTokenIsInvalid(): void
    {
        $token = TokenFactory::create([
            'expired_at' => \Minz\Time::fromNow(30, 'days'),
        ]);
        $account = AccountFactory::create([
            'access_token' => $token->token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account->id,
            'access_token' => 'not the token',
        ]);

        $this->assertResponseCode($response, 400);
        $user = utils\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testLoginFailsIfAccessTokenIsExpired(): void
    {
        $token = TokenFactory::create([
            'expired_at' => \Minz\Time::ago(30, 'days'),
        ]);
        $account = AccountFactory::create([
            'access_token' => $token->token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account->id,
            'access_token' => $token->token,
        ]);

        $this->assertResponseCode($response, 400);
        $user = utils\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testLoginFailsIfAccessTokenIsNotSet(): void
    {
        $token = TokenFactory::create([
            'expired_at' => \Minz\Time::fromNow(30, 'days'),
        ]);
        $account = AccountFactory::create([
            'access_token' => null,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account->id,
            'access_token' => null,
        ]);

        $this->assertResponseCode($response, 400);
        $user = utils\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testLogoutRedirectsToShow(): void
    {
        $service = $this->fake('randomElement', ['flusio', 'freshrss']);
        $this->loginUser([
            'preferred_service' => $service,
        ]);

        $response = $this->appRun('POST', '/account/logout', [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        if ($service === 'flusio') {
            $expected_location = 'https://app.flus.fr';
        } else {
            $expected_location = 'https://flus.io';
        }
        $this->assertResponseCode($response, 302, $expected_location);
        $user = utils\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testLogoutDoesNotLogOutIfCsrfIsInvalid(): void
    {
        $this->loginUser();

        $response = $this->appRun('POST', '/account/logout', [
            'csrf' => 'not the token',
        ]);

        $this->assertResponseCode($response, 302, '/');
        $user = utils\CurrentUser::get();
        $this->assertNotNull($user);
    }

    public function testProfileRendersCorrectly(): void
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/profile');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'accounts/profile.phtml');
    }

    public function testProfileFailsIfNotConnected(): void
    {
        $response = $this->appRun('GET', '/account/profile');

        $this->assertResponseCode($response, 401);
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileChangesAddressAndRedirects(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertSame($email, $account->email);
        $this->assertSame($address['first_name'], $account->address_first_name);
        $this->assertSame($address['last_name'], $account->address_last_name);
        $this->assertSame($address['address1'], $account->address_address1);
        $this->assertSame($address['postcode'], $account->address_postcode);
        $this->assertSame($address['city'], $account->address_city);
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileAcceptsNoPhysicalAddress(string $email, array $address): void
    {
        $user = $this->loginUser();
        unset($address['address1']);
        unset($address['postcode']);
        unset($address['city']);

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertSame($email, $account->email);
        $this->assertSame($address['first_name'], $account->address_first_name);
        $this->assertSame($address['last_name'], $account->address_last_name);
        $this->assertEmpty($account->address_address1);
        $this->assertEmpty($account->address_postcode);
        $this->assertEmpty($account->address_city);
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileFailsIfNotConnected(string $email, array $address): void
    {
        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 401);
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileFailsIfCsrfIsInvalid(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => 'not the token',
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileFailsWithInvalidEmail(string $email, array $address): void
    {
        $user = $this->loginUser();
        $email = $this->fake('domainName');

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel valide.');
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertNotSame($email, $account->email);
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileFailsWithMissingEmail(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel.');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileFailsWithMissingFirstName(string $email, array $address): void
    {
        $user = $this->loginUser();
        unset($address['first_name']);

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre prénom est obligatoire');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileFailsWithMissingLastName(string $email, array $address): void
    {
        $user = $this->loginUser();
        unset($address['last_name']);

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre nom est obligatoire');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileFailsWithMissingAddress1(string $email, array $address): void
    {
        $user = $this->loginUser();
        unset($address['address1']);

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre adresse est incomplète');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileFailsWithMissingPostcode(string $email, array $address): void
    {
        $user = $this->loginUser();
        unset($address['postcode']);

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre adresse est incomplète');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileFailsWithMissingCity(string $email, array $address): void
    {
        $user = $this->loginUser();
        unset($address['city']);

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre adresse est incomplète');
    }

    /**
     * @dataProvider addressesProvider
     *
     * @param AccountAddress $address
     */
    public function testUpdateProfileFailsWithInvalidCountry(string $email, array $address): void
    {
        $user = $this->loginUser();
        $address['country'] = 'invalid';

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez un pays de la liste.');
    }

    public function testSetReminderChangesReminder(): void
    {
        $old_reminder = $this->fake('boolean');
        $new_reminder = !$old_reminder;
        $user = $this->loginUser([
            'reminder' => $old_reminder,
        ]);

        $response = $this->appRun('POST', '/account/reminder', [
            'csrf' => \Minz\Csrf::generate(),
            'reminder' => $new_reminder,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertSame($new_reminder, $account->reminder);
    }

    public function testSetReminderFailsIfNotConnected(): void
    {
        $old_reminder = $this->fake('boolean');
        $new_reminder = !$old_reminder;
        $account = AccountFactory::create([
            'reminder' => $old_reminder,
        ]);

        $response = $this->appRun('POST', '/account/reminder', [
            'csrf' => \Minz\Csrf::generate(),
            'reminder' => $new_reminder,
        ]);

        $this->assertResponseCode($response, 401);
        $account = $account->reload();
        $this->assertNotNull($account);
        $this->assertSame($old_reminder, $account->reminder);
    }

    public function testSetReminderFailsIfCsrfIsInvalid(): void
    {
        $old_reminder = $this->fake('boolean');
        $new_reminder = !$old_reminder;
        $user = $this->loginUser([
            'reminder' => $old_reminder,
        ]);

        $response = $this->appRun('POST', '/account/reminder', [
            'csrf' => 'not the token',
            'reminder' => $new_reminder,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertSame($old_reminder, $account->reminder);
    }

    /**
     * @return array<array{string, AccountAddress}>
     */
    public function addressesProvider(): array
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                $faker->email,
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
