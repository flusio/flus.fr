<?php

namespace Website\api;

use Website\models;

class AccountsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;

    /**
     * @dataProvider showParamsProvider
     */
    public function testShowReturnsAccountId($email)
    {
        $account_id = $this->create('account', [
            'email' => $email,
        ]);

        $response = $this->appRun('GET', '/api/account', [
            'email' => $email,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 200, null, [
            'Content-Type' => 'application/json'
        ]);
        $output = json_decode($response->render(), true);
        $this->assertSame($account_id, $output['id']);
    }

    /**
     * @dataProvider showParamsProvider
     */
    public function testShowCreatesAccountIfDoesNotExist($email)
    {
        $account_dao = new models\dao\Account();

        $this->assertSame(0, $account_dao->count());

        $response = $this->appRun('GET', '/api/account', [
            'email' => $email,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 200, null, [
            'Content-Type' => 'application/json'
        ]);
        $this->assertSame(1, $account_dao->count());
        $account = new models\Account($account_dao->listAll()[0]);
        $this->assertSame($email, $account->email);
        $output = json_decode($response->render(), true);
        $this->assertSame($account->id, $output['id']);
    }

    /**
     * @dataProvider showParamsProvider
     */
    public function testShowAcceptsExpiredAtToCreateAccount($email)
    {
        $account_dao = new models\dao\Account();
        $expired_at = $this->fake('dateTime');

        $response = $this->appRun('GET', '/api/account', [
            'email' => $email,
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertSame(1, $account_dao->count());
        $account = new models\Account($account_dao->listAll()[0]);
        $this->assertSame($expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }

    /**
     * @dataProvider showParamsProvider
     */
    public function testShowFailsIfMissingAuth($email)
    {
        $account_id = $this->create('account', [
            'email' => $email,
        ]);

        $response = $this->appRun('GET', '/api/account', [
            'email' => $email,
        ]);

        $this->assertResponse($response, 401);
    }

    public function testShowFailsIfEmailIsInvalid()
    {
        $email = $this->fake('word');

        $response = $this->appRun('GET', '/api/account', [
            'email' => $email,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 400, 'L’adresse courriel que vous avez fournie est invalide.');
    }

    public function testLoginUrlSetsAccessTokenReturnsAUrl()
    {
        $this->freeze($this->fake('unixTime'));
        $account_dao = new models\dao\Account();
        $token_dao = new models\dao\Token();
        $account_id = $this->create('account', [
            'access_token' => null,
        ]);

        $response = $this->appRun('GET', '/api/account/login-url', [
            'account_id' => $account_id,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 200, null, [
            'Content-Type' => 'application/json'
        ]);

        $account = new models\Account($account_dao->find($account_id));
        $token = new models\Token($token_dao->find($account->access_token));
        $this->assertTrue($token->isValid());
        $this->assertTrue($token->expiresIn(10, 'minutes'));

        $expected_url = \Minz\Url::absoluteFor('account login', [
            'account_id' => $account->id,
            'access_token' => $token->token,
        ]);
        $output = json_decode($response->render(), true);
        $this->assertSame($expected_url, $output['url']);
    }

    public function testLoginUrlFailsIfMissingAuth()
    {
        $this->freeze($this->fake('unixTime'));
        $account_dao = new models\dao\Account();
        $token_dao = new models\dao\Token();
        $account_id = $this->create('account', [
            'access_token' => null,
        ]);

        $response = $this->appRun('GET', '/api/account/login-url', [
            'account_id' => $account_id,
        ]);

        $this->assertResponse($response, 401);
        $account = new models\Account($account_dao->find($account_id));
        $this->assertNull($account->access_token);
    }

    public function testLoginUrlFailsIfAccountIsInvalid()
    {
        $this->freeze($this->fake('unixTime'));
        $account_dao = new models\dao\Account();
        $token_dao = new models\dao\Token();
        $account_id = $this->create('account', [
            'access_token' => null,
        ]);

        $response = $this->appRun('GET', '/api/account/login-url', [
            'account_id' => 'not the id',
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 404);
        $account = new models\Account($account_dao->find($account_id));
        $this->assertNull($account->access_token);
    }

    public function testExpiredAtReturnsExpiredAt()
    {
        $expired_at = $this->fake('dateTime')->format(\Minz\Model::DATETIME_FORMAT);
        $account_id = $this->create('account', [
            'expired_at' => $expired_at,
        ]);

        $response = $this->appRun('GET', '/api/account/expired-at', [
            'account_id' => $account_id,
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 200, null, [
            'Content-Type' => 'application/json'
        ]);
        $output = json_decode($response->render(), true);
        $this->assertSame($expired_at, $output['expired_at']);
    }

    public function testExpiredAtFailsIfMissingAuth()
    {
        $expired_at = $this->fake('dateTime')->format(\Minz\Model::DATETIME_FORMAT);
        $account_id = $this->create('account', [
            'expired_at' => $expired_at,
        ]);

        $response = $this->appRun('GET', '/api/account/expired-at', [
            'account_id' => $account_id,
        ]);

        $this->assertResponse($response, 401);
    }

    public function testExpiredAtFailsIfAccountIsInvalid()
    {
        $expired_at = $this->fake('dateTime')->format(\Minz\Model::DATETIME_FORMAT);
        $account_id = $this->create('account', [
            'expired_at' => $expired_at,
        ]);

        $response = $this->appRun('GET', '/api/account/expired-at', [
            'account_id' => 'not the id',
        ], [
            'PHP_AUTH_USER' => \Minz\Configuration::$application['flus_private_key'],
        ]);

        $this->assertResponse($response, 404);
    }

    public function showParamsProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                $faker->email,
            ];
        }

        return $datasets;
    }
}
