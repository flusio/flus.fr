<?php

namespace Website\controllers\api;

use tests\factories\AccountFactory;
use Website\models;

class AccountsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\TimeHelper;
    use \Minz\Tests\ResponseAsserts;

    #[\PHPUnit\Framework\Attributes\DataProvider('showParamsProvider')]
    public function testShowReturnsAccountId(string $email): void
    {
        $account = AccountFactory::create([
            'email' => $email,
        ]);

        /** @var \Minz\Response */
        $response = $this->appRun('GET', '/api/account', [
            'email' => $email,
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseHeaders($response, [
            'Content-Type' => 'application/json'
        ]);
        $output = json_decode($response->render(), true);
        $this->assertSame($account->id, $output['id']);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('showParamsProvider')]
    public function testShowCreatesAccountIfDoesNotExist(string $email): void
    {
        $this->freeze();
        $this->assertSame(0, models\Account::count());

        /** @var \Minz\Response */
        $response = $this->appRun('GET', '/api/account', [
            'email' => $email,
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseHeaders($response, [
            'Content-Type' => 'application/json'
        ]);
        $this->assertSame(1, models\Account::count());
        $account = models\Account::take();
        $this->assertNotNull($account);
        $this->assertSame($email, $account->email);
        $output = json_decode($response->render(), true);
        $this->assertSame($account->id, $output['id']);
        $expected_expired_at = \Minz\Time::fromNow(31, 'days');
        $this->assertSame(
            $expected_expired_at->format(\Minz\Database\Column::DATETIME_FORMAT),
            $output['expired_at']
        );
    }

    public function testShowUpdatesLastSyncAt(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $email = $this->fake('email');
        $account = AccountFactory::create([
            'email' => $email,
            'last_sync_at' => null,
        ]);

        $response = $this->appRun('GET', '/api/account', [
            'email' => $email,
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 200);
        $account = $account->reload();
        $this->assertEquals($now, $account->last_sync_at);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('showParamsProvider')]
    public function testShowFailsIfMissingAuth(string $email): void
    {
        $account = AccountFactory::create([
            'email' => $email,
        ]);

        $response = $this->appRun('GET', '/api/account', [
            'email' => $email,
        ]);

        $this->assertResponseCode($response, 401);
    }

    public function testShowFailsIfEmailIsInvalid(): void
    {
        $email = $this->fake('word');

        $response = $this->appRun('GET', '/api/account', [
            'email' => $email,
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel valide.');
    }

    public function testLoginUrlSetsAccessTokenReturnsAUrl(): void
    {
        $this->freeze();
        $account = AccountFactory::create([
            'access_token' => null,
        ]);

        /** @var \Minz\Response */
        $response = $this->appRun('GET', '/api/account/login-url', [
            'account_id' => $account->id,
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseHeaders($response, [
            'Content-Type' => 'application/json'
        ]);

        $account = $account->reload();
        $this->assertNotNull($account->access_token);
        $token = models\Token::find($account->access_token);
        $this->assertNotNull($token);
        $this->assertTrue($token->isValid());
        $this->assertTrue($token->expiresIn(10, 'minutes'));

        $expected_url = \Minz\Url::absoluteFor('account login', [
            'account_id' => $account->id,
            'access_token' => $token->token,
        ]);
        $output = json_decode($response->render(), true);
        $this->assertSame($expected_url, $output['url']);
    }

    public function testLoginUrlFailsIfMissingAuth(): void
    {
        $this->freeze();
        $account = AccountFactory::create([
            'access_token' => null,
        ]);

        $response = $this->appRun('GET', '/api/account/login-url', [
            'account_id' => $account->id,
        ]);

        $this->assertResponseCode($response, 401);
        $account = $account->reload();
        $this->assertNull($account->access_token);
    }

    public function testLoginUrlFailsIfAccountIsInvalid(): void
    {
        $this->freeze();
        $account = AccountFactory::create([
            'access_token' => null,
        ]);

        $response = $this->appRun('GET', '/api/account/login-url', [
            'account_id' => 'not the id',
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 404);
        $account = $account->reload();
        $this->assertNull($account->access_token);
    }

    public function testExpiredAtReturnsExpiredAt(): void
    {
        $expired_at = $this->fake('dateTime');
        $account = AccountFactory::create([
            'expired_at' => $expired_at,
        ]);

        /** @var \Minz\Response */
        $response = $this->appRun('GET', '/api/account/expired-at', [
            'account_id' => $account->id,
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseHeaders($response, [
            'Content-Type' => 'application/json'
        ]);
        $output = json_decode($response->render(), true);
        $this->assertSame(
            $expired_at->format(\Minz\Database\Column::DATETIME_FORMAT),
            $output['expired_at']
        );
    }

    public function testExpiredAtUpdatesLastSyncAt(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $account = AccountFactory::create([
            'last_sync_at' => null,
        ]);

        $response = $this->appRun('GET', '/api/account/expired-at', [
            'account_id' => $account->id,
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 200);
        $account = $account->reload();
        $this->assertEquals($now, $account->last_sync_at);
    }

    public function testExpiredAtFailsIfMissingAuth(): void
    {
        $account = AccountFactory::create();

        $response = $this->appRun('GET', '/api/account/expired-at', [
            'account_id' => $account->id,
        ]);

        $this->assertResponseCode($response, 401);
    }

    public function testExpiredAtFailsIfAccountIsInvalid(): void
    {
        $account = AccountFactory::create();

        $response = $this->appRun('GET', '/api/account/expired-at', [
            'account_id' => 'not the id',
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 404);
    }

    public function testSyncReturnsExpiredAt(): void
    {
        $expired_at = $this->fakeUnique('dateTime');
        $account = AccountFactory::create([
            'expired_at' => $expired_at,
        ]);

        /** @var \Minz\Response */
        $response = $this->appRun('POST', '/api/accounts/sync', [
            'account_ids' => json_encode([$account->id]),
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseHeaders($response, [
            'Content-Type' => 'application/json',
        ]);
        $result = json_decode($response->render(), true);
        $this->assertArrayHasKey($account->id, $result);
        $this->assertEquals(
            $expired_at->format(\Minz\Database\Column::DATETIME_FORMAT),
            $result[$account->id]
        );
    }

    public function testSyncUpdatesLastSyncAt(): void
    {
        $now = $this->fake('dateTime');
        $this->freeze($now);
        $account = AccountFactory::create([
            'last_sync_at' => null,
        ]);

        $response = $this->appRun('POST', '/api/accounts/sync', [
            'account_ids' => json_encode([$account->id]),
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 200);
        $account = $account->reload();
        $this->assertEquals($now, $account->last_sync_at);
    }

    public function testSyncDoesNotReturnUnknownAccounts(): void
    {
        $account_id = 'not-an-id';

        /** @var \Minz\Response */
        $response = $this->appRun('POST', '/api/accounts/sync', [
            'account_ids' => json_encode([$account_id]),
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 200);
        $result = json_decode($response->render(), true);
        $this->assertArrayNotHasKey($account_id, $result);
    }

    public function testSyncIgnoresNullAccountIds(): void
    {
        $expired_at = $this->fakeUnique('dateTime');
        $account = AccountFactory::create([
            'expired_at' => $expired_at,
        ]);

        /** @var \Minz\Response */
        $response = $this->appRun('POST', '/api/accounts/sync', [
            'account_ids' => json_encode([null, $account->id]),
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseHeaders($response, [
            'Content-Type' => 'application/json',
        ]);
        $result = json_decode($response->render(), true);
        $this->assertFalse(isset($result[null]));
        $this->assertArrayHasKey($account->id, $result);
        $this->assertEquals(
            $expired_at->format(\Minz\Database\Column::DATETIME_FORMAT),
            $result[$account->id]
        );
    }

    public function testSyncFailsIfAccountIdsIsNotValidJson(): void
    {
        $account = AccountFactory::create();

        /** @var \Minz\Response */
        $response = $this->appRun('POST', '/api/accounts/sync', [
            'account_ids' => $account->id,
        ], [
            'Authorization' => $this->authorizationHeader(),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseHeaders($response, [
            'Content-Type' => 'application/json',
        ]);
        $result = json_decode($response->render(), true);
        $this->assertArrayHasKey('error', $result);
        $this->assertSame('account_ids is not a valid JSON array', $result['error']);
    }

    public function testSyncFailsIfMissingAuth(): void
    {
        $account = AccountFactory::create();

        $response = $this->appRun('POST', '/api/accounts/sync', [
            'account_ids' => json_encode([$account->id]),
        ]);

        $this->assertResponseCode($response, 401);
    }

    /**
     * @return array<array{string}>
     */
    public static function showParamsProvider(): array
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

    private function authorizationHeader(): string
    {
        $header = ':' . \Minz\Configuration::$application['flus_private_key'];
        return 'Basic ' . base64_encode($header);
    }
}
