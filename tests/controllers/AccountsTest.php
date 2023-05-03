<?php

namespace Website\controllers;

use tests\factories\AccountFactory;
use tests\factories\PaymentFactory;
use tests\factories\TokenFactory;
use Website\models;
use Website\utils;

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
     */
    public function testShowRendersCorrectly($email, $address)
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
     */
    public function testShowRendersFutureExpiration($email, $address)
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
     */
    public function testShowRendersPriorExpiration($email, $address)
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
     */
    public function testShowRendersIfNoExpiration($email, $address)
    {
        $expired_at = new \DateTime('@0');
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
     */
    public function testShowRendersIfOngoingAndUnpaidPayment($email, $address)
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
     */
    public function testShowCompletesAnOngoingAndPaidPayment($email, $address)
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
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponseNotContains($response, 'Votre paiement est en cours de traitement');
        $payment = $payment->reload();
        $this->assertNotNull($payment->completed_at);
    }

    public function testShowRedirectsIfNoAddress()
    {
        $email = $this->fake('email');
        $this->loginUser([
            'email' => $email,
            'address_first_name' => null,
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponseCode($response, 302, '/account/address');
    }

    public function testShowFailsIfNotConnected()
    {
        $response = $this->appRun('GET', '/account');

        $this->assertResponseCode($response, 401);
        $this->assertResponseContains($response, 'Désolé, mais vous n’êtes pas connecté‧e');
    }

    public function testLoginRedirectsToShow()
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

    public function testLoginDeletesTheAccessToken()
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

    public function testLoginRedirectsIfAlreadyConnected()
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

    public function testLoginRedirectsIfAlreadyConnectedAsAdmin()
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

    public function testLoginFailsIfAccountIdIsInvalid()
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

    public function testLoginFailsIfAccessTokenIsInvalid()
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

    public function testLoginFailsIfAccessTokenIsExpired()
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

    public function testLoginFailsIfAccessTokenIsNotSet()
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

    public function testLogoutRedirectsToShow()
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

    public function testLogoutDoesNotLogOutIfCsrfIsInvalid()
    {
        $this->loginUser();

        $response = $this->appRun('POST', '/account/logout', [
            'csrf' => 'not the token',
        ]);

        $this->assertResponseCode($response, 302, '/');
        $user = utils\CurrentUser::get();
        $this->assertNotNull($user);
    }

    public function testAddressRendersCorrectly()
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/address');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'accounts/address.phtml');
    }

    public function testAddressFailsIfNotConnected()
    {
        $response = $this->appRun('GET', '/account/address');

        $this->assertResponseCode($response, 401);
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressChangesAddressAndRedirects($email, $address)
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $account = models\Account::find($user['account_id']);
        $this->assertSame($email, $account->email);
        $this->assertSame($address['first_name'], $account->address_first_name);
        $this->assertSame($address['last_name'], $account->address_last_name);
        $this->assertSame($address['address1'], $account->address_address1);
        $this->assertSame($address['postcode'], $account->address_postcode);
        $this->assertSame($address['city'], $account->address_city);
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressAcceptsNoPhysicalAddress($email, $address)
    {
        $user = $this->loginUser();
        unset($address['address1']);
        unset($address['postcode']);
        unset($address['city']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 302, '/account');
        $account = models\Account::find($user['account_id']);
        $this->assertSame($email, $account->email);
        $this->assertSame($address['first_name'], $account->address_first_name);
        $this->assertSame($address['last_name'], $account->address_last_name);
        $this->assertEmpty($account->address_address1);
        $this->assertEmpty($account->address_postcode);
        $this->assertEmpty($account->address_city);
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressFailsIfNotConnected($email, $address)
    {
        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 401);
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressFailsIfCsrfIsInvalid($email, $address)
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => 'not the token',
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressFailsWithInvalidEmail($email, $address)
    {
        $user = $this->loginUser();
        $email = $this->fake('domainName');

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel valide.');
        $account = models\Account::find($user['account_id']);
        $this->assertNotSame($email, $account->email);
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressFailsWithMissingEmail($email, $address)
    {
        $user = $this->loginUser();

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel.');
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressFailsWithMissingFirstName($email, $address)
    {
        $user = $this->loginUser();
        unset($address['first_name']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre prénom est obligatoire');
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressFailsWithMissingLastName($email, $address)
    {
        $user = $this->loginUser();
        unset($address['last_name']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre nom est obligatoire');
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressFailsWithMissingAddress1($email, $address)
    {
        $user = $this->loginUser();
        unset($address['address1']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre adresse est incomplète');
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressFailsWithMissingPostcode($email, $address)
    {
        $user = $this->loginUser();
        unset($address['postcode']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre adresse est incomplète');
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressFailsWithMissingCity($email, $address)
    {
        $user = $this->loginUser();
        unset($address['city']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Votre adresse est incomplète');
    }

    /**
     * @dataProvider addressesProvider
     */
    public function testUpdateAddressFailsWithInvalidCountry($email, $address)
    {
        $user = $this->loginUser();
        $address['country'] = 'invalid';

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => \Minz\Csrf::generate(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez un pays de la liste.');
    }

    public function testSetReminderChangesReminder()
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
        $this->assertSame($new_reminder, $account->reminder);
    }

    public function testSetReminderFailsIfNotConnected()
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
        $this->assertSame($old_reminder, $account->reminder);
    }

    public function testSetReminderFailsIfCsrfIsInvalid()
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
        $this->assertSame($old_reminder, $account->reminder);
    }

    public function addressesProvider()
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
                ],
            ];
        }

        return $datasets;
    }
}
