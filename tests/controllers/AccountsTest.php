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
    public function testShowRedirectsToRenew(string $email, array $address): void
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

        $this->assertResponseCode($response, 302, '/account/renew');
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
            'show_address' => true,
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
    public function testUpdateProfileAcceptsLegalEntityInfo(string $email, array $address): void
    {
        $user = $this->loginUser();
        $faker = \Faker\Factory::create('fr_FR');
        $legal_name = $faker->company();
        $vat_number = $faker->vat(); // @phpstan-ignore-line
        $address['legal_name'] = $legal_name;

        $response = $this->appRun('POST', '/account/profile', [
            'csrf' => \Minz\Csrf::generate(),
            'entity_type' => 'legal',
            'email' => $email,
            'show_address' => true,
            'address' => $address,
            'company_vat_number' => $vat_number,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertSame($email, $account->email);
        $this->assertSame($vat_number, $account->company_vat_number);
        $this->assertSame('', $account->address_first_name);
        $this->assertSame('', $account->address_last_name);
        $this->assertSame($address['legal_name'], $account->address_legal_name);
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
            'show_address' => false,
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
            'show_address' => true,
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
            'show_address' => true,
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
            'show_address' => true,
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
            'show_address' => true,
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
            'show_address' => true,
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
            'show_address' => true,
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
            'show_address' => true,
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
            'show_address' => true,
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
            'show_address' => true,
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
            'show_address' => true,
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

    public function testManagedAccountsRendersCorrectly(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
        ]);

        $response = $this->appRun('GET', '/account/managed');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'accounts/managed.phtml');
    }

    public function testManagedAccountsFailsIfNotConnected(): void
    {
        $response = $this->appRun('GET', '/account/managed');

        $this->assertResponseCode($response, 401);
    }

    public function testManagedAccountsFailsIfNotLegalEntity(): void
    {
        $this->loginUser([
            'entity_type' => 'natural',
        ]);

        $response = $this->appRun('GET', '/account/managed');

        $this->assertResponseCode($response, 404);
    }

    public function testAddManagedAccountsCreatesAccount(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
        ]);
        $account = models\Account::take();
        $this->assertNotNull($account);
        $email = $this->fake('email');

        $response = $this->appRun('POST', '/account/managed', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
        ]);

        $this->assertResponseCode($response, 302, '/account/managed');
        $managed_account = models\Account::findBy(['email' => $email]);
        $this->assertNotNull($managed_account);
        $this->assertSame($account->id, $managed_account->managed_by_id);
    }

    public function testAddManagedAccountsUpdatesExistingAccount(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
            'expired_at' => \Minz\Time::fromNow(2, 'month'),
        ]);
        $account = models\Account::take();
        $this->assertNotNull($account);
        $managed_account = AccountFactory::create([
            'managed_by_id' => null,
            'expired_at' => \Minz\Time::fromNow(1, 'month'),
            'reminder' => true,
        ]);

        $response = $this->appRun('POST', '/account/managed', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $managed_account->email,
        ]);

        $this->assertResponseCode($response, 302, '/account/managed');
        $managed_account = $managed_account->reload();
        $this->assertNotNull($managed_account);
        $this->assertSame($account->id, $managed_account->managed_by_id);
        $this->assertSame(
            $account->expired_at->getTimestamp(),
            $managed_account->expired_at->getTimestamp()
        );
        $this->assertFalse($managed_account->reminder);
    }

    public function testAddManagedAccountsFailsIfCsrfIsInvalid(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
        ]);
        $email = $this->fake('email');

        $response = $this->appRun('POST', '/account/managed', [
            'csrf' => 'not a token',
            'email' => $email,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertFalse(models\Account::existsBy(['email' => $email]));
    }

    public function testAddManagedAccountsFailsIfEmailIsInvalid(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
        ]);
        $email = 'not an email';

        $response = $this->appRun('POST', '/account/managed', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertFalse(models\Account::existsBy(['email' => $email]));
    }

    public function testAddManagedAccountsFailsIfDefaultAccount(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
        ]);
        $default_account = models\Account::defaultAccount();

        $response = $this->appRun('POST', '/account/managed', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $default_account->email,
        ]);

        $this->assertResponseCode($response, 400);
        $default_account = $default_account->reload();
        $this->assertNotNull($default_account);
        $this->assertNull($default_account->managed_by_id);
    }

    public function testAddManagedAccountsFailsIfAlreadyManaged(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
            'expired_at' => \Minz\Time::fromNow(2, 'month'),
        ]);
        $account = AccountFactory::create([
            'entity_type' => 'legal',
        ]);
        $managed_account = AccountFactory::create([
            'managed_by_id' => $account->id,
        ]);

        $response = $this->appRun('POST', '/account/managed', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $managed_account->email,
        ]);

        $this->assertResponseCode($response, 400);
        $managed_account = $managed_account->reload();
        $this->assertNotNull($managed_account);
        $this->assertSame($account->id, $managed_account->managed_by_id);
    }

    public function testAddManagedAccountsFailsIfNotLegalEntity(): void
    {
        $this->loginUser([
            'entity_type' => 'natural',
        ]);
        $email = $this->fake('email');

        $response = $this->appRun('POST', '/account/managed', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
        ]);

        $this->assertResponseCode($response, 404);
        $this->assertFalse(models\Account::existsBy(['email' => $email]));
    }

    public function testAddManagedAccountsFailsIfNotConnected(): void
    {
        $email = $this->fake('email');

        $response = $this->appRun('POST', '/account/managed', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
        ]);

        $this->assertResponseCode($response, 401);
        $this->assertFalse(models\Account::existsBy(['email' => $email]));
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
