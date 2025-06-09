<?php

namespace Website\controllers;

use tests\factories\PaymentFactory;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testPayRendersCorrectly(): void
    {
        $payment = PaymentFactory::create();

        $response = $this->appRun('GET', "/payments/{$payment->id}/pay");

        $this->assertResponseCode($response, 200);
        $this->assertResponseTemplateName($response, 'stripe/redirection.phtml');
    }

    public function testPayConfiguresStripe(): void
    {
        $session_id = $this->fake('regexify', 'cs_test_[\w\d]{56}');
        $payment = PaymentFactory::create([
            'session_id' => $session_id,
        ]);

        /** @var \Minz\Response */
        $response = $this->appRun('GET', "/payments/{$payment->id}/pay");

        $output = $response->output();
        $this->assertInstanceOf(\Minz\Output\Template::class, $output);
        $template = $output->template();
        $this->assertInstanceOf(\Minz\Template\Simple::class, $template);
        $context = $template->context();
        $headers = $response->headers(raw: true);
        /** @var array<string, string> */
        $csp = $headers['Content-Security-Policy'];

        $this->assertSame(
            \Minz\Configuration::$application['stripe_public_key'],
            $context['stripe_public_key']
        );
        $this->assertSame(
            $session_id,
            $context['stripe_session_id']
        );
        $this->assertSame(
            "'self' js.stripe.com",
            $csp['default-src']
        );
        $this->assertSame(
            "'self' 'unsafe-inline' js.stripe.com",
            $csp['script-src']
        );
    }

    public function testPayWithUnknownIdReturnsANotFound(): void
    {
        $response = $this->appRun('GET', "/payments/unknown/pay");

        $this->assertResponseCode($response, 404);
    }

    public function testPayWithPaidPaymentReturnsBadRequest(): void
    {
        $payment = PaymentFactory::create([
            'completed_at' => $this->fake('dateTime'),
        ]);

        $response = $this->appRun('GET', "/payments/{$payment->id}/pay");

        $this->assertResponseCode($response, 400);
    }

    public function testSucceededRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/merci');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre paiement a bien été pris en compte');
    }

    public function testCanceledRendersCorrectly(): void
    {
        $response = $this->appRun('GET', '/annulation');

        $this->assertResponseCode($response, 200);
        $this->assertResponseContains($response, 'Votre paiement a bien été annulé');
    }
}
