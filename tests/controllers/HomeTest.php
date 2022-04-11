<?php

namespace Website\controllers;

class HomeTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;
    use \Minz\Tests\MailerAsserts;

    public function testIndexRendersCorrectly()
    {
        $response = $this->appRun('GET', '/');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Une plateforme pour agréger, stocker et partager votre veille');
        $this->assertResponsePointer($response, 'home/index.phtml');
    }

    public function testProjectRendersCorrectly()
    {
        $response = $this->appRun('GET', '/projet');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Le projet');
        $this->assertResponsePointer($response, 'home/project.phtml');
    }

    public function testPricingRendersCorrectly()
    {
        $response = $this->appRun('GET', '/tarifs');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Tarifs');
        $this->assertResponsePointer($response, 'home/pricing.phtml');
    }

    public function testTourRedirectsToTourNews()
    {
        $response = $this->appRun('GET', '/visite');

        $this->assertResponseCode($response, 302, '/visite/journal');
    }

    /**
     * @dataProvider tourPagesProvider
     */
    public function testTourPageRendersCorrectly($page)
    {
        $response = $this->appRun('GET', "/visite/{$page}");

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Visite guidée');
    }

    public function testTourPageFailsIfPageUnknown()
    {
        $response = $this->appRun('GET', '/visite/unknown');

        $this->assertResponseCode($response, 404);
    }

    public function testCreditsRendersCorrectly()
    {
        $response = $this->appRun('GET', '/credits');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Crédits');
        $this->assertResponsePointer($response, 'home/credits.phtml');
    }

    public function testLegalRendersCorrectly()
    {
        $response = $this->appRun('GET', '/mentions-legales');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Mentions légales');
        $this->assertResponsePointer($response, 'home/legal.phtml');
    }

    public function testCgvRendersCorrectly()
    {
        $response = $this->appRun('GET', '/cgv');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Conditions Générales de Vente');
        $this->assertResponsePointer($response, 'home/cgv.phtml');
    }

    public function testRobotsRendersCorrectly()
    {
        $response = $this->appRun('GET', '/robots.txt');

        $this->assertResponseCode($response, 200);
    }

    public function testSitemapRendersCorrectly()
    {
        $response = $this->appRun('GET', '/sitemap.xml');

        $this->assertResponseCode($response, 200);
    }

    public function testContactRendersCorrectly()
    {
        $response = $this->appRun('GET', '/contact');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'home/contact.phtml');
    }

    public function testSendContactEmailSendsEmails()
    {
        $email = $this->fake('email');
        $subject = $this->fake('sentence');
        $content = implode("\n", $this->fake('paragraphs'));

        $this->assertEmailsCount(0);

        $response = $this->appRun('POST', '/contact', [
            'email' => $email,
            'subject' => $subject,
            'content' => $content,
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre message a bien été envoyé.');
        $this->assertResponsePointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(1);

        $email_sent = \Minz\Tests\Mailer::take(0);
        $this->assertEmailSubject($email_sent, '[Flus] Contact : ' . $subject);
        $this->assertEmailContainsTo($email_sent, 'support@example.com');
        $this->assertEmailContainsReplyTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, nl2br($content));
    }

    public function testSendContactEmailFailsIfEmailIsMissing()
    {
        $response = $this->appRun('POST', '/contact', [
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'L’adresse courriel est obligatoire.');
        $this->assertResponsePointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactEmailFailsIfEmailIsInvalid()
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('word'),
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'L’adresse courriel que vous avez fournie est invalide.');
        $this->assertResponsePointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactEmailFailsIfSubjectIsMissing()
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'content' => implode("\n", $this->fake('paragraphs')),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Le sujet est obligatoire');
        $this->assertResponsePointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactEmailFailsIfContentIsMissing()
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'subject' => $this->fake('sentence'),
        ]);

        $this->assertResponseCode($response, 400);
        $this->assertResponseContains($response, 'Le message est obligatoire');
        $this->assertResponsePointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactEmailFailsIfWebsiteIsPresent()
    {
        // The website parameter MUST NOT be sent: it’s a trap for the bots.
        // The field is hidden with CSS so people don't fill it.
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
            'website' => $this->fake('url'),
        ]);

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre message a bien été envoyé.');
        $this->assertResponsePointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSecurityRendersCorrectly()
    {
        $response = $this->appRun('GET', '/securite');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'home/security.phtml');
    }

    public function testSecurityTxtRendersCorrectly()
    {
        $response = $this->appRun('GET', '/.well-known/security.txt');

        $this->assertResponseCode($response, 200);
        $this->assertResponsePointer($response, 'home/security.txt');
    }

    public function tourPagesProvider()
    {
        return [
            ['flux'],
            ['signets'],
            ['journal'],
            ['collections'],
            ['pocket'],
            ['opml'],
        ];
    }
}
