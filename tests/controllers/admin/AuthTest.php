<?php

namespace Website\controllers\admin;

class AuthTest extends \PHPUnit\Framework\TestCase
{
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testLogin(): void
    {
        $response = $this->appRun('GET', '/admin/login');

        $this->assertResponseCode($response, 200);
    }

    public function testLoginWhenConnected(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('GET', '/admin/login');

        $this->assertResponseCode($response, 302, '/admin');
    }

    public function testLoginWithFromParameter(): void
    {
        /** @var \Minz\Response */
        $response = $this->appRun('GET', '/admin/login', [
            'from' => 'home',
        ]);

        $this->assertResponseCode($response, 200);
        $output = $response->output();
        $this->assertInstanceOf(\Minz\Output\Template::class, $output);
        $template = $output->template();
        $this->assertInstanceOf(\Minz\Template\Simple::class, $template);
        $context = $template->context();
        $this->assertSame('home', $context['from']);
    }

    public function testCreateSession(): void
    {
        $response = $this->appRun('POST', '/admin/login', [
            'csrf' => \Website\Csrf::generate(),
            'password' => 'secret',
        ]);

        $this->assertResponseCode($response, 302, '/admin?status=connected');
        $this->assertSame('the administrator', $_SESSION['account_id']);
    }

    public function testCreateSessionWhenConnected(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/login', [
            'csrf' => \Website\Csrf::generate(),
            'password' => 'secret',
        ]);

        $this->assertResponseCode($response, 302, '/admin');
    }

    public function testCreateSessionWithFromParameter(): void
    {
        $response = $this->appRun('POST', '/admin/login', [
            'csrf' => \Website\Csrf::generate(),
            'password' => 'secret',
            'from' => urlencode('home'),
        ]);

        $this->assertResponseCode($response, 302, '/?status=connected');
        $this->assertSame('the administrator', $_SESSION['account_id']);
    }

    public function testCreateSessionFailsIfCsrfIsWrong(): void
    {
        $response = $this->appRun('POST', '/admin/login', [
            'csrf' => 'not the token',
            'password' => 'secret',
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
        $this->assertArrayNotHasKey('account_id', $_SESSION);
    }

    public function testCreateSessionFailsIfPasswordIsInvalid(): void
    {
        $response = $this->appRun('POST', '/admin/login', [
            'csrf' => \Website\Csrf::generate(),
            'password' => 'not the secret',
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertArrayNotHasKey('account_id', $_SESSION, 'Le mot de passe semble invalide');
    }

    public function testDeleteSession(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/logout', [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/');
        $this->assertArrayNotHasKey('account_id', $_SESSION);
    }

    public function testDeleteSessionWhenUnconnected(): void
    {
        $response = $this->appRun('POST', '/admin/logout', [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/');
    }

    public function testDeleteSessionFailsIfCsrfIsWrong(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/logout', [
            'csrf' => 'not the token',
        ]);

        $this->assertResponseCode($response, 302, '/');
        $this->assertSame('the administrator', $_SESSION['account_id']);
    }
}
