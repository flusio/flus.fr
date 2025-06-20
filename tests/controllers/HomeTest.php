<?php

namespace Website\controllers;

use Website\forms;

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

    public function testSendContactMessageFailsIfWebsiteIsPresent(): void
    {
        // The website parameter MUST NOT be sent: it’s a trap for the bots.
        // The field is hidden with CSS so people don't fill it.
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
            'website' => $this->fake('url'),
            'csrf_token' => $this->csrfToken(forms\Contact::class),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre message a bien été envoyé.');
        $this->assertResponseTemplateName($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactMessageFailsIfCsrfTokenIsInvalid(): void
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
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
}
