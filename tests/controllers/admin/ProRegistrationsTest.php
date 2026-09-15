<?php

namespace Website\controllers\admin;

use Website\models;
use tests\factories\ProRegistrationFactory;

class ProRegistrationsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testIndexRendersCorrectly(): void
    {
        $this->loginAdmin();
        $email = $this->fake('email');
        ProRegistrationFactory::create([
            'email' => $email,
        ]);

        $response = $this->appRun('GET', '/admin/pro-registrations');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, $email);
        $this->assertResponseTemplateName($response, 'admin/pro_registrations/index.phtml');
    }

    public function testIndexRendersCsv(): void
    {
        $this->loginAdmin();
        $email = $this->fake('email');
        ProRegistrationFactory::create([
            'email' => $email,
            'organisation' => '=HYPERLINK("example")',
        ]);

        $response = $this->appRun('GET', '/admin/pro-registrations', [
            'format' => 'csv',
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, "\"{$email}\"");
        $this->assertResponseContains($response, '"\'=HYPERLINK(""example"")"');
        $this->assertResponseTemplateName($response, 'admin/pro_registrations/index.txt');
    }

    public function testIndexFailsIfNotConnected(): void
    {
        $response = $this->appRun('GET', '/admin/pro-registrations');

        $this->assertResponseCode($response, 302, '/admin/login');
    }

    public function testDeleteDeletesProRegistration(): void
    {
        $this->loginAdmin();
        $pro_registration = ProRegistrationFactory::create();

        $response = $this->appRun('POST', "/admin/pro-registrations/{$pro_registration->id}/delete", [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin/pro-registrations?status=pro_registration_deleted');
        $this->assertFalse(models\ProRegistration::exists($pro_registration->id));
    }

    public function testDeleteFailsIfInvalidId(): void
    {
        $this->loginAdmin();

        $response = $this->appRun('POST', '/admin/pro-registrations/invalid/delete', [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 404);
    }

    public function testDeleteFailsIfCsrfIsInvalid(): void
    {
        $this->loginAdmin();
        $pro_registration = ProRegistrationFactory::create();

        $response = $this->appRun('POST', "/admin/pro-registrations/{$pro_registration->id}/delete", [
            'csrf' => 'not the token',
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertTrue(models\ProRegistration::exists($pro_registration->id));
    }

    public function testDeleteFailsIfNotConnected(): void
    {
        $pro_registration = ProRegistrationFactory::create();

        $response = $this->appRun('POST', "/admin/pro-registrations/{$pro_registration->id}/delete", [
            'csrf' => \Website\Csrf::generate(),
        ]);

        $this->assertResponseCode($response, 302, '/admin/login');
        $this->assertTrue(models\ProRegistration::exists($pro_registration->id));
    }
}
