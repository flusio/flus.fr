<?php

namespace Website\controllers;

use AltchaOrg;
use tests\factories\ProRegistrationFactory;
use Website\forms;
use Website\models;
use Website\services;

class HomeTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\CsrfHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\MailerAsserts;

    public function testIndexRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Flus, le complément éditorial de votre veille');
        $this->assertResponseTemplateName($response, 'home/index.phtml');
    }

    public function testPricingRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/tarifs');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Tarifs');
        $this->assertResponseTemplateName($response, 'home/pricing.phtml');
    }

    public function testCreditsRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/credits');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Crédits');
        $this->assertResponseTemplateName($response, 'home/credits.phtml');
    }

    public function testRobotsRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/robots.txt');

        $this->assertResponseCode($response, 200);
    }

    public function testSitemapRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/sitemap.xml');

        $this->assertResponseCode($response, 200);
    }

    public function testContactRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/contact');

        $this->assertResponseCode($response, 200);
        $this->assertResponseTemplateName($response, 'home/contact.phtml');
    }

    public function testSendContactMessageSendsEmails(): void
    {
        $email = $this->fake('email');
        $subject = $this->fake('sentence');
        $content = implode("\n", $this->fake('paragraphs'));

        $this->assertEmailsCount(0);

        $response = $this->appRun('POST', '/contact', [
            'email' => $email,
            'subject' => $subject,
            'content' => $content,
            'altcha' => $this->altchaPayload(),
            'csrf_token' => $this->csrfToken(forms\Contact::class),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre message a bien été envoyé.');
        $this->assertResponseTemplateName($response, 'home/contact.phtml');
        $this->assertEmailsCount(1);

        $email_sent = \Minz\Tests\Mailer::take(0);
        $this->assertNotNull($email_sent);
        $this->assertEmailSubject($email_sent, '[Flus] Contact : ' . $subject);
        $this->assertEmailContainsTo($email_sent, 'support@example.com');
        $this->assertEmailContainsReplyTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, nl2br($content));
    }

    public function testSendContactMessageFailsIfEmailIsMissing(): void
    {
        $response = $this->appRun('POST', '/contact', [
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
            'altcha' => $this->altchaPayload(),
            'csrf_token' => $this->csrfToken(forms\Contact::class),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel.');
        $this->assertResponseTemplateName($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactMessageFailsIfEmailIsInvalid(): void
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('word'),
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
            'altcha' => $this->altchaPayload(),
            'csrf_token' => $this->csrfToken(forms\Contact::class),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel valide.');
        $this->assertResponseTemplateName($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactMessageFailsIfSubjectIsMissing(): void
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'content' => implode("\n", $this->fake('paragraphs')),
            'altcha' => $this->altchaPayload(),
            'csrf_token' => $this->csrfToken(forms\Contact::class),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez un sujet.');
        $this->assertResponseTemplateName($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactMessageFailsIfContentIsMissing(): void
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'subject' => $this->fake('sentence'),
            'csrf_token' => $this->csrfToken(forms\Contact::class),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez un message.');
        $this->assertResponseTemplateName($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactMessageFailsIfAltchaIsInvalid(): void
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
            'altcha' => $this->altchaPayload(valid: false),
            'csrf_token' => $this->csrfToken(forms\Contact::class),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Le captcha est invalide');
        $this->assertResponseTemplateName($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactMessageFailsIfCsrfTokenIsInvalid(): void
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
            'altcha' => $this->altchaPayload(),
            'csrf_token' => 'not a token',
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Une vérification de sécurité a échoué');
        $this->assertResponseTemplateName($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSecurityRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/securite');

        $this->assertResponseCode($response, 200);
        $this->assertResponseTemplateName($response, 'home/security.phtml');
    }

    public function testSecurityTxtRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/.well-known/security.txt');

        $this->assertResponseCode($response, 200);
        $this->assertResponseTemplateName($response, 'home/security.txt');
    }

    public function testPressKitRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/kit-presse');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Kit de presse');
        $this->assertResponseTemplateName($response, 'home/press_kit.phtml');
    }

    public function testProRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/pro');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Flus pour les équipes');
        $this->assertResponseTemplateName($response, 'home/pro.phtml');
    }

    public function testCreateProRegistrationCreatesProRegistration(): void
    {
        $email = $this->fake('email');
        $organisation = $this->fake('company');

        $this->assertSame(0, models\ProRegistration::count());

        $response = $this->appRun('POST', '/pro', [
            'email' => $email,
            'organisation' => $organisation,
            'altcha' => $this->altchaPayload(),
            'csrf_token' => $this->csrfToken(forms\ProRegistration::class),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'C’est noté');
        $this->assertResponseTemplateName($response, 'home/pro.phtml');
        $this->assertSame(1, models\ProRegistration::count());
        $pro_registration = models\ProRegistration::take();
        $this->assertNotNull($pro_registration);
        $this->assertSame($email, $pro_registration->email);
        $this->assertSame($organisation, $pro_registration->organisation);
    }

    public function testCreateProRegistrationAcceptsMissingOrganisation(): void
    {
        $email = $this->fake('email');

        $response = $this->appRun('POST', '/pro', [
            'email' => $email,
            'altcha' => $this->altchaPayload(),
            'csrf_token' => $this->csrfToken(forms\ProRegistration::class),
        ]);

        $this->assertResponseCode($response, 200);
        $pro_registration = models\ProRegistration::take();
        $this->assertNotNull($pro_registration);
        $this->assertSame($email, $pro_registration->email);
        $this->assertSame('', $pro_registration->organisation);
    }

    public function testCreateProRegistrationUpdatesExistingProRegistration(): void
    {
        $email = $this->fake('email');
        $old_organisation = $this->fakeUnique('company');
        $new_organisation = $this->fakeUnique('company');
        $pro_registration = ProRegistrationFactory::create([
            'email' => $email,
            'organisation' => $old_organisation,
        ]);

        $response = $this->appRun('POST', '/pro', [
            'email' => $email,
            'organisation' => $new_organisation,
            'altcha' => $this->altchaPayload(),
            'csrf_token' => $this->csrfToken(forms\ProRegistration::class),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'C’est noté');
        $this->assertSame(1, models\ProRegistration::count());
        $pro_registration = $pro_registration->reload();
        $this->assertSame($new_organisation, $pro_registration->organisation);
    }

    public function testCreateProRegistrationFailsIfEmailIsMissing(): void
    {
        $response = $this->appRun('POST', '/pro', [
            'organisation' => $this->fake('company'),
            'altcha' => $this->altchaPayload(),
            'csrf_token' => $this->csrfToken(forms\ProRegistration::class),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel.');
        $this->assertResponseTemplateName($response, 'home/pro.phtml');
        $this->assertSame(0, models\ProRegistration::count());
    }

    public function testCreateProRegistrationFailsIfEmailIsInvalid(): void
    {
        $response = $this->appRun('POST', '/pro', [
            'email' => $this->fake('word'),
            'altcha' => $this->altchaPayload(),
            'csrf_token' => $this->csrfToken(forms\ProRegistration::class),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Saisissez une adresse courriel valide.');
        $this->assertSame(0, models\ProRegistration::count());
    }

    public function testCreateProRegistrationFailsIfAltchaIsInvalid(): void
    {
        $response = $this->appRun('POST', '/pro', [
            'email' => $this->fake('email'),
            'altcha' => $this->altchaPayload(valid: false),
            'csrf_token' => $this->csrfToken(forms\ProRegistration::class),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertSame(0, models\ProRegistration::count());
    }

    public function testCreateProRegistrationFailsIfCsrfIsInvalid(): void
    {
        $response = $this->appRun('POST', '/pro', [
            'email' => $this->fake('email'),
            'altcha' => $this->altchaPayload(),
            'csrf_token' => 'not the token',
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertSame(0, models\ProRegistration::count());
    }

    private function altchaPayload(bool $valid = true): string
    {
        $altcha_service = new services\AltchaService();

        $challenge = $altcha_service->buildChallenge(cost: 1);

        if ($valid) {
            $solution = $altcha_service->solveChallenge($challenge);
        } else {
            $solution = new AltchaOrg\Altcha\Solution(0, '');
        }

        $payload = $altcha_service->buildPayload($challenge, $solution);

        return $payload->toBase64();
    }
}
