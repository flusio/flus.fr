<?php

namespace Website;

class PaymentsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testPayRendersCorrectly()
    {
        $payment_id = $this->create('payment');

        $response = $this->appRun('GET', "/payments/{$payment_id}/pay");

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'stripe/redirection.phtml');
    }

    public function testPayConfiguresStripe()
    {
        $session_id = $this->fake('regexify', 'cs_test_[\w\d]{56}');
        $payment_id = $this->create('payment', [
            'session_id' => $session_id,
        ]);

        $response = $this->appRun('GET', "/payments/{$payment_id}/pay");

        $variables = $response->output()->variables();
        $headers = $response->headers(true);
        $csp = $headers['Content-Security-Policy'];

        $this->assertSame(
            \Minz\Configuration::$application['stripe_public_key'],
            $variables['stripe_public_key']
        );
        $this->assertSame(
            $session_id,
            $variables['stripe_session_id']
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

    public function testPayWithUnknownIdReturnsANotFound()
    {
        $response = $this->appRun('GET', "/payments/unknown/pay");

        $this->assertResponse($response, 404);
    }

    public function testPayWithPaidPaymentReturnsBadRequest()
    {
        $payment_id = $this->create('payment', [
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);

        $response = $this->appRun('GET', "/payments/{$payment_id}/pay");

        $this->assertResponse($response, 400);
    }

    public function testSucceededRendersCorrectly()
    {
        $response = $this->appRun('GET', '/merci');

        $this->assertResponse($response, 200, 'Votre paiement a bien été pris en compte');
    }

    public function testCanceledRendersCorrectly()
    {
        $response = $this->appRun('GET', '/annulation');

        $this->assertResponse($response, 200, 'Votre paiement a bien été annulé');
    }
}
