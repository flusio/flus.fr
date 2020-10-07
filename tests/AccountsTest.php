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
}
