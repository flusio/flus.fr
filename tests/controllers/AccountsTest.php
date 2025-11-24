<?php

namespace Website\controllers;

use tests\factories\AccountFactory;
use tests\factories\TokenFactory;
use Website\auth;
use Website\forms;
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
    use \Minz\Tests\CsrfHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\TimeHelper;

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
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
        $this->assertResponseContains($response, 'Connexion requise');
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
        $user = auth\CurrentUser::get();
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
        $user = auth\CurrentUser::get();
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
        $user = auth\CurrentUser::get();
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
        $user = auth\CurrentUser::get();
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

        $this->assertResponseCode($response, 401);
        $user = auth\CurrentUser::get();
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

        $this->assertResponseCode($response, 401);
        $user = auth\CurrentUser::get();
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

        $this->assertResponseCode($response, 401);
        $user = auth\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testLogoutRedirectsToShow(): void
    {
        $service = $this->fake('randomElement', ['flus', 'freshrss']);
        $this->loginUser([
            'preferred_service' => $service,
        ]);

        $response = $this->appRun('POST', '/account/logout', [
            'csrf' => \Website\Csrf::generate(),
        ]);

        if ($service === 'flus') {
            $expected_location = 'https://app.flus.fr';
        } else {
            $expected_location = 'https://rss.flus.fr';
        }
        $this->assertResponseCode($response, 302, $expected_location);
        $user = auth\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testLogoutDoesNotLogOutIfCsrfIsInvalid(): void
    {
        $this->loginUser();

        $response = $this->appRun('POST', '/account/logout', [
            'csrf' => 'not the token',
        ]);

        $this->assertResponseCode($response, 302, '/');
        $user = auth\CurrentUser::get();
        $this->assertNotNull($user);
    }

    public function testProfileRendersCorrectly(): void
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/profile');

        $this->assertResponseCode($response, 200);
        $this->assertResponseTemplateName($response, 'accounts/profile.phtml');
    }

    public function testProfileFailsIfNotConnected(): void
    {
        $response = $this->appRun('GET', '/account/profile');

        $this->assertResponseCode($response, 401);
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileChangesAddressAndRedirects(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
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
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileAcceptsLegalEntityInfo(string $email, array $address): void
    {
        $user = $this->loginUser();
        $faker = \Faker\Factory::create('fr_FR');
        $legal_name = $faker->company();
        $vat_number = $faker->vat(); // @phpstan-ignore-line
        $address['legal_name'] = $legal_name;

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'legal',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_legal_name' => $address['legal_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
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
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileAcceptsNoPhysicalAddress(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => false,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
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
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileResetsManagedAccountsIfEntityTypeIsNatural(string $email, array $address): void
    {
        $user = $this->loginUser();
        $managed_account = AccountFactory::create([
            'managed_by_id' => $user['account_id'],
        ]);

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $managed_account = $managed_account->reload();
        $this->assertNull($managed_account->managed_by_id);
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertSame(0, $account->countManagedAccounts());
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsIfNotConnected(string $email, array $address): void
    {
        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $this->assertResponseCode($response, 401);
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsIfCsrfIsInvalid(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => 'not the token',
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsWithInvalidEmail(string $email, array $address): void
    {
        $user = $this->loginUser();
        $email = $this->fake('domainName');

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel valide.');
        $account = models\Account::find($user['account_id']);
        $this->assertNotNull($account);
        $this->assertNotSame($email, $account->email);
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsWithMissingEmail(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => '',
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel.');
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsWithMissingFirstName(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => '',
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre prénom est obligatoire');
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsWithMissingLastName(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => '',
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre nom est obligatoire');
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsWithMissingLegalName(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'legal',
            'email' => $email,
            'show_address' => true,
            'address_legal_name' => '',
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre raison sociale est obligatoire');
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsWithMissingAddress1(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => '',
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre adresse est incomplète');
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsWithMissingPostcode(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => '',
            'address_city' => $address['city'],
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre adresse est incomplète');
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsWithMissingCity(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => '',
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre adresse est incomplète');
    }

    /**
     * @param AccountAddress $address
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('addressesProvider')]
    public function testUpdateProfileFailsWithInvalidCountry(string $email, array $address): void
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/profile', [
            'csrf_token' => $this->csrfToken(forms\Profile::class),
            'entity_type' => 'natural',
            'email' => $email,
            'show_address' => true,
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'address_country' => 'invalid',
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
            'csrf' => \Website\Csrf::generate(),
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
            'csrf' => \Website\Csrf::generate(),
            'reminder' => $new_reminder,
        ]);

        $this->assertResponseCode($response, 401);
        $account = $account->reload();
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
        $this->assertResponseTemplateName($response, 'accounts/managed.phtml');
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
            'csrf' => \Website\Csrf::generate(),
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
            'csrf' => \Website\Csrf::generate(),
            'email' => $managed_account->email,
        ]);

        $this->assertResponseCode($response, 302, '/account/managed');
        $managed_account = $managed_account->reload();
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
            'csrf' => \Website\Csrf::generate(),
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
            'csrf' => \Website\Csrf::generate(),
            'email' => $default_account->email,
        ]);

        $this->assertResponseCode($response, 400);
        $default_account = $default_account->reload();
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
            'csrf' => \Website\Csrf::generate(),
            'email' => $managed_account->email,
        ]);

        $this->assertResponseCode($response, 400);
        $managed_account = $managed_account->reload();
        $this->assertSame($account->id, $managed_account->managed_by_id);
    }

    public function testAddManagedAccountsFailsIfNotLegalEntity(): void
    {
        $this->loginUser([
            'entity_type' => 'natural',
        ]);
        $email = $this->fake('email');

        $response = $this->appRun('POST', '/account/managed', [
            'csrf' => \Website\Csrf::generate(),
            'email' => $email,
        ]);

        $this->assertResponseCode($response, 404);
        $this->assertFalse(models\Account::existsBy(['email' => $email]));
    }

    public function testAddManagedAccountsFailsIfNotConnected(): void
    {
        $email = $this->fake('email');

        $response = $this->appRun('POST', '/account/managed', [
            'csrf' => \Website\Csrf::generate(),
            'email' => $email,
        ]);

        $this->assertResponseCode($response, 401);
        $this->assertFalse(models\Account::existsBy(['email' => $email]));
    }

    public function testDeleteManagedAccountsRemovesAccountFromManaged(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
        ]);
        $account = models\Account::take();
        $this->assertNotNull($account);
        $managed_account = AccountFactory::create([
            'managed_by_id' => $account->id,
        ]);

        $response = $this->appRun('POST', "/account/managed/{$managed_account->id}/delete", [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/account/managed');
        $managed_account = $managed_account->reload();
        $this->assertNull($managed_account->managed_by_id);
    }

    public function testDeleteManagedAccountsFailsIfNotManagedByCurrentAccount(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
        ]);
        $managed_account = AccountFactory::create([
            'managed_by_id' => $managed_account = AccountFactory::create()->id,
        ]);

        $response = $this->appRun('POST', "/account/managed/{$managed_account->id}/delete", [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/account/managed');
        $managed_account = $managed_account->reload();
        $this->assertNotNull($managed_account->managed_by_id);
    }

    public function testDeleteManagedAccountsFailsIfIdDoesNotExist(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
        ]);

        $response = $this->appRun('POST', '/account/managed/not-exist/delete', [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 404);
    }

    public function testDeleteManagedAccountsFailsIfCsrfIsInvalid(): void
    {
        $this->loginUser([
            'entity_type' => 'legal',
        ]);
        $account = models\Account::take();
        $this->assertNotNull($account);
        $managed_account = AccountFactory::create([
            'managed_by_id' => $account->id,
        ]);

        $response = $this->appRun('POST', "/account/managed/{$managed_account->id}/delete", [
            'csrf' => 'not a token',
        ]);

        $this->assertResponseCode($response, 302, '/account/managed');
        $managed_account = $managed_account->reload();
        $this->assertNotNull($managed_account->managed_by_id);
    }

    public function testDeleteManagedAccountsFailsIfNotConnected(): void
    {
        $managed_account = AccountFactory::create([
            'managed_by_id' => AccountFactory::create()->id,
        ]);

        $response = $this->appRun('POST', "/account/managed/{$managed_account->id}/delete", [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 401);
        $managed_account = $managed_account->reload();
        $this->assertNotNull($managed_account->managed_by_id);
    }

    /**
     * @return array<array{string, AccountAddress}>
     */
    public static function addressesProvider(): array
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
