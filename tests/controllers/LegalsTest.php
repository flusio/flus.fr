<?php

namespace Website\controllers;

class LegalsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\MailerAsserts;

    public function testIndexRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/informations-legales');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Informations légales');
    }

    public function testNoticesRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/mentions-legales');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Mentions légales');
    }

    public function testGeneralTermsRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/conditions-generales');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Conditions générales');
    }

    public function testPrivacyPolicyRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/politique-confidentialite');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Politique de confidentialité');
    }

    public function testCookiesPolicyRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/politique-cookies');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Politique de cookies');
    }

    public function testCgvRedirectsToGeneralTerms(): void
    {
        $response = $this->appRun('GET', '/cgv');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Conditions Générales de Vente');
    }
}
