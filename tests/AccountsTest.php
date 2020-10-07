<?php

namespace Website;

class AccountsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;

    public function testShowRendersCorrectly()
    {
        $email = $this->fake('email');
        $this->loginUser([
            'email' => $email,
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponse($response, 200, $email);
        $this->assertPointer($response, 'accounts/show.phtml');
    }

    public function testShowRendersFutureExpiration()
    {
        $this->freeze($this->fake('dateTime'));
        $expired_at = \Minz\Time::fromNow($this->fake('randomDigitNotNull'), 'days');
        $this->loginUser([
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponse($response, 200, 'Votre abonnement expirera le');
    }

    public function testShowRendersPriorExpiration()
    {
        $this->freeze($this->fake('dateTime'));
        $expired_at = \Minz\Time::ago($this->fake('randomDigitNotNull'), 'days');
        $this->loginUser([
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponse($response, 200, 'Votre abonnement a expiré le');
    }

    public function testShowRendersIfNoExpiration()
    {
        $expired_at = new \DateTime('1970-01-01');
        $this->loginUser([
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);

        $response = $this->appRun('GET', '/account');

        $this->assertResponse($response, 200, 'Vous bénéficiez d’un abonnement gratuit');
    }

    public function testShowFailsIfNotConnected()
    {
        $response = $this->appRun('GET', '/account');

        $this->assertResponse($response, 401, 'Désolé, mais vous n’êtes pas connecté‧e');
    }

    public function testLoginRedirectsToShow()
    {
        $expired_at = \Minz\Time::fromNow(30, 'days');
        $token = $this->create('token', [
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $account_id = $this->create('account', [
            'access_token' => $token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account_id,
            'access_token' => $token,
        ]);

        $this->assertResponse($response, 302, '/account');
        $user = utils\CurrentUser::get();
        $this->assertNotNull($user);
        $this->assertSame($account_id, $user['account_id']);
    }

    public function testLoginDeletesTheAccessToken()
    {
        $token_dao = new models\dao\Token();
        $account_dao = new models\dao\Account();
        $expired_at = \Minz\Time::fromNow(30, 'days');
        $token = $this->create('token', [
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $account_id = $this->create('account', [
            'access_token' => $token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account_id,
            'access_token' => $token,
        ]);

        $account = new models\Account($account_dao->find($account_id));
        $this->assertNull($account->access_token);
        $this->assertFalse($token_dao->exists($token));
    }

    public function testLoginRedirectsIfAlreadyConnected()
    {
        $expired_at = \Minz\Time::fromNow(30, 'days');
        $token = $this->create('token', [
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $user = $this->loginUser([
            'access_token' => $token,
        ]);
        $account_id = $user['account_id'];

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account_id,
            'access_token' => $token,
        ]);

        $this->assertResponse($response, 302, '/account');
        $user = utils\CurrentUser::get();
        $this->assertNotNull($user);
        $this->assertSame($account_id, $user['account_id']);
    }

    public function testLoginRedirectsIfAlreadyConnectedAsAdmin()
    {
        $user = $this->loginAdmin();
        $expired_at = \Minz\Time::fromNow(30, 'days');
        $token = $this->create('token', [
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $account_id = $this->create('account', [
            'access_token' => $token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account_id,
            'access_token' => $token,
        ]);

        $this->assertResponse($response, 302, '/account');
        $user = utils\CurrentUser::get();
        $this->assertNotNull($user);
        $this->assertSame($account_id, $user['account_id']);
    }

    public function testLoginFailsIfAccountIdIsInvalid()
    {
        $expired_at = \Minz\Time::fromNow(30, 'days');
        $token = $this->create('token', [
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $account_id = $this->create('account', [
            'access_token' => $token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => 'not the id',
            'access_token' => $token,
        ]);

        $this->assertResponse($response, 404);
        $user = utils\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testLoginFailsIfAccessTokenIsInvalid()
    {
        $expired_at = \Minz\Time::fromNow(30, 'days');
        $token = $this->create('token', [
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $account_id = $this->create('account', [
            'access_token' => $token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account_id,
            'access_token' => 'not the token',
        ]);

        $this->assertResponse($response, 400);
        $user = utils\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testLoginFailsIfAccessTokenIsExpired()
    {
        $expired_at = \Minz\Time::ago(30, 'days');
        $token = $this->create('token', [
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $account_id = $this->create('account', [
            'access_token' => $token,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account_id,
            'access_token' => $token,
        ]);

        $this->assertResponse($response, 400);
        $user = utils\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testLoginFailsIfAccessTokenIsNotSet()
    {
        $expired_at = \Minz\Time::ago(30, 'days');
        $token = $this->create('token', [
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $account_id = $this->create('account', [
            'access_token' => null,
        ]);

        $response = $this->appRun('GET', '/account/login', [
            'account_id' => $account_id,
            'access_token' => null,
        ]);

        $this->assertResponse($response, 400);
        $user = utils\CurrentUser::get();
        $this->assertNull($user);
    }

    public function testAddressRendersCorrectly()
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/address');

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'accounts/address.phtml');
    }

    public function testAddressFailsIfNotConnected()
    {
        $response = $this->appRun('GET', '/account/address');

        $this->assertResponse($response, 401);
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressChangesAddressAndRedirects($email, $address)
    {
        $user = $this->loginUser();
        $account_dao = new models\dao\Account();

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponse($response, 302, '/account');
        $account = new models\Account($account_dao->find($user['account_id']));
        $this->assertSame($email, $account->email);
        $this->assertSame($address['first_name'], $account->address_first_name);
        $this->assertSame($address['last_name'], $account->address_last_name);
        $this->assertSame($address['address1'], $account->address_address1);
        $this->assertSame($address['postcode'], $account->address_postcode);
        $this->assertSame($address['city'], $account->address_city);
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressFailsIfNotConnected($email, $address)
    {
        $response = $this->appRun('POST', '/account/address', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponse($response, 401);
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressFailsIfCsrfIsInvalid($email, $address)
    {
        $user = $this->loginUser();
        $account_dao = new models\dao\Account();

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => 'not the token',
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'Une vérification de sécurité a échoué');
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressFailsWithInvalidEmail($email, $address)
    {
        $user = $this->loginUser();
        $account_dao = new models\dao\Account();
        $email = $this->fake('domainName');

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'L’adresse courriel que vous avez fournie est invalide');
        $account = new models\Account($account_dao->find($user['account_id']));
        $this->assertNotSame($email, $account->email);
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressFailsWithMissingEmail($email, $address)
    {
        $user = $this->loginUser();
        $account_dao = new models\dao\Account();

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'L’adresse courriel est obligatoire');
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressFailsWithMissingFirstName($email, $address)
    {
        $user = $this->loginUser();
        $account_dao = new models\dao\Account();
        unset($address['first_name']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'Votre prénom est obligatoire');
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressFailsWithMissingLastName($email, $address)
    {
        $user = $this->loginUser();
        $account_dao = new models\dao\Account();
        unset($address['last_name']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'Votre nom est obligatoire');
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressFailsWithMissingAddress1($email, $address)
    {
        $user = $this->loginUser();
        $account_dao = new models\dao\Account();
        unset($address['address1']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'Votre adresse est obligatoire');
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressFailsWithMissingPostcode($email, $address)
    {
        $user = $this->loginUser();
        $account_dao = new models\dao\Account();
        unset($address['postcode']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'Votre code postal est obligatoire');
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressFailsWithMissingCity($email, $address)
    {
        $user = $this->loginUser();
        $account_dao = new models\dao\Account();
        unset($address['city']);

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'Votre ville est obligatoire');
    }

    /**
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddressFailsWithInvalidCountry($email, $address)
    {
        $user = $this->loginUser();
        $account_dao = new models\dao\Account();
        $address['country'] = 'invalid';

        $response = $this->appRun('POST', '/account/address', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'email' => $email,
            'address' => $address,
        ]);

        $this->assertResponse($response, 400, 'Le pays que vous avez renseigné est invalide.');
    }

    public function updateAddressProvider()
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
