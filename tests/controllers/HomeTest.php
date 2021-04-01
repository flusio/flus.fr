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

        $this->assertResponse($response, 200, 'Le média social qui change<br />notre rapport à l’information.');
        $this->assertPointer($response, 'home/index.phtml');
    }

    public function testProjectRendersCorrectly()
    {
        $response = $this->appRun('GET', '/projet');

        $this->assertResponse($response, 200, 'Le projet');
        $this->assertPointer($response, 'home/project.phtml');
    }

    public function testPricingRendersCorrectly()
    {
        $response = $this->appRun('GET', '/tarifs');

        $this->assertResponse($response, 200, 'Tarifs');
        $this->assertPointer($response, 'home/pricing.phtml');
    }

    public function testCreditsRendersCorrectly()
    {
        $response = $this->appRun('GET', '/credits');

        $this->assertResponse($response, 200, 'Crédits');
        $this->assertPointer($response, 'home/credits.phtml');
    }

    public function testLegalRendersCorrectly()
    {
        $response = $this->appRun('GET', '/mentions-legales');

        $this->assertResponse($response, 200, 'Mentions légales');
        $this->assertPointer($response, 'home/legal.phtml');
    }

    public function testCgvRendersCorrectly()
    {
        $response = $this->appRun('GET', '/cgv');

        $this->assertResponse($response, 200, 'Conditions Générales de Vente');
        $this->assertPointer($response, 'home/cgv.phtml');
    }

    public function testRobotsRendersCorrectly()
    {
        $response = $this->appRun('GET', '/robots.txt');

        $this->assertResponse($response, 200);
    }

    public function testSitemapRendersCorrectly()
    {
        $response = $this->appRun('GET', '/sitemap.xml');

        $this->assertResponse($response, 200);
    }

    public function testContactRendersCorrectly()
    {
        $response = $this->appRun('GET', '/contact');

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'home/contact.phtml');
    }

    public function testSendContactEmailSendsTwoEmails()
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

        $this->assertResponse($response, 200, 'Votre message a bien été envoyé.');
        $this->assertPointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(2);

        $email_sent = \Minz\Tests\Mailer::take(0);
        $this->assertEmailSubject($email_sent, '[Flus] Contact : ' . $subject);
        $this->assertEmailContainsTo($email_sent, 'support@example.com');
        $this->assertEmailContainsReplyTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, nl2br($content));

        $email_sent = \Minz\Tests\Mailer::take(1);
        $this->assertEmailSubject($email_sent, '[Flus] Votre message a bien été envoyé');
        $this->assertEmailFrom($email_sent, 'root@localhost');
        $this->assertEmailContainsTo($email_sent, $email);
        $this->assertEmailContainsBody($email_sent, $subject);
    }

    public function testSendContactEmailFailsIfEmailIsMissing()
    {
        $response = $this->appRun('POST', '/contact', [
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
        ]);

        $this->assertResponse($response, 400, 'L’adresse courriel est obligatoire.');
        $this->assertPointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactEmailFailsIfEmailIsInvalid()
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('word'),
            'subject' => $this->fake('sentence'),
            'content' => implode("\n", $this->fake('paragraphs')),
        ]);

        $this->assertResponse($response, 400, 'L’adresse courriel que vous avez fournie est invalide.');
        $this->assertPointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactEmailFailsIfSubjectIsMissing()
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'content' => implode("\n", $this->fake('paragraphs')),
        ]);

        $this->assertResponse($response, 400, 'Le sujet est obligatoire');
        $this->assertPointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }

    public function testSendContactEmailFailsIfContentIsMissing()
    {
        $response = $this->appRun('POST', '/contact', [
            'email' => $this->fake('email'),
            'subject' => $this->fake('sentence'),
        ]);

        $this->assertResponse($response, 400, 'Le message est obligatoire');
        $this->assertPointer($response, 'home/contact.phtml');
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

        $this->assertResponse($response, 200, 'Votre message a bien été envoyé.');
        $this->assertPointer($response, 'home/contact.phtml');
        $this->assertEmailsCount(0);
    }
}
