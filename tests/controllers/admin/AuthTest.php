<?php

namespace Website\controllers\admin;

class AuthTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testLogin()
    {
        $response = $this->appRun('GET', '/admin/login');

        $this->assertResponseCode($response, 200);
    }

    public function testLoginWhenConnected()
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin/login');

        $this->assertResponseCode($response, 302, '/admin');
    }

    public function testLoginWithFromParameter()
    {
        /** @var \Minz\Response */
        $response = $this->appRun('GET', '/admin/login', [
            'from' => 'home',
        ]);

        $this->assertResponseCode($response, 200);
        /** @var \Minz\Output\View */
        $output = $response->output();
        $variables = $output->variables();
        $this->assertArrayHasKey('from', $variables);
        $this->assertSame('home', $variables['from']);
    }

    public function testCreateSession()
    {
        $response = $this->appRun('POST', '/admin/login', [
            'csrf' => \Minz\Csrf::generate(),
            'password' => 'secret',
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=connected');
        $this->assertSame('the administrator', $_SESSION['account_id']);
    }

    public function testCreateSessionWhenConnected()
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/login', [
            'csrf' => \Minz\Csrf::generate(),
            'password' => 'secret',
        ]);

        $this->assertResponseCode($response, 302, '/admin');
    }

    public function testCreateSessionWithFromParameter()
    {
        $response = $this->appRun('POST', '/admin/login', [
            'csrf' => \Minz\Csrf::generate(),
            'password' => 'secret',
            'from' => urlencode('home'),
        ]);

        $this->assertResponseCode($response, 302, '/?status=connected');
        $this->assertSame('the administrator', $_SESSION['account_id']);
    }

    public function testCreateSessionFailsIfCsrfIsWrong()
    {
        $response = $this->appRun('POST', '/admin/login', [
            'csrf' => 'not the token',
            'password' => 'secret',
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
        $this->assertArrayNotHasKey('account_id', $_SESSION);
    }

    public function testCreateSessionFailsIfPasswordIsInvalid()
    {
        $response = $this->appRun('POST', '/admin/login', [
            'csrf' => \Minz\Csrf::generate(),
            'password' => 'not the secret',
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertArrayNotHasKey('account_id', $_SESSION, 'Le mot de passe semble invalide');
    }

    public function testDeleteSession()
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/logout', [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/');
        $this->assertArrayNotHasKey('account_id', $_SESSION);
    }

    public function testDeleteSessionWhenUnconnected()
    {
        $response = $this->appRun('POST', '/admin/logout', [
            'csrf' => \Minz\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/');
    }

    public function testDeleteSessionFailsIfCsrfIsWrong()
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/logout', [
            'csrf' => 'not the token',
        ]);

        $this->assertResponseCode($response, 302, '/');
        $this->assertSame('the administrator', $_SESSION['account_id']);
    }
}
