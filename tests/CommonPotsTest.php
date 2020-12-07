<?php

namespace Website;

class CommonPotsTest extends \PHPUnit\Framework\TestCase
{
    use \tests\FakerHelper;
    use \tests\LoginHelper;
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\FactoriesHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testShowPublicRendersCorrectly()
    {
        $common_pot_expenses = $this->fake('numberBetween', 100, 499);
        $common_pot_revenues = $this->fake('numberBetween', 500, 100000);
        $subscriptions_revenues = $this->fake('numberBetween', 100, 100000);
        $this->create('common_pot_payment', [
            'amount' => $common_pot_expenses,
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => $common_pot_revenues,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $this->create('payment', [
            'type' => 'subscription',
            'amount' => $subscriptions_revenues,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);

        $response = $this->appRun('GET', '/cagnotte');

        $expected_amount = ($common_pot_revenues - $common_pot_expenses) / 100;
        $expected_formatted_amount = number_format($expected_amount, 2, ',', '&nbsp') . '&nbsp;€';
        $this->assertResponse($response, 200, $expected_formatted_amount);
        $this->assertPointer($response, 'common_pots/show.phtml');
    }

    /**
     * @dataProvider addressProvider
     */
    public function testContributionRendersCorrectly($address)
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/common-pot/contribute');

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'common_pots/contribution.phtml');
    }

    public function testContributionRedirectsIfNoAddress()
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/common-pot/contribute');

        $this->assertResponse($response, 302, '/account/address');
    }

    /**
     * @dataProvider addressProvider
     */
    public function testContributionFailsIfNotConnected($address)
    {
        $this->create('account', [
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/common-pot/contribute');

        $this->assertResponse($response, 401);
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeRedirectsCorrectly($amount, $address)
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $payment_dao = new models\dao\Payment();
        $payment_id = $payment_dao->take()['id'];
        $redirect_to = "/payments/{$payment_id}/pay";
        $this->assertResponse($response, 302, $redirect_to);
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeAcceptsFloatAmounts($amount, $address)
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $amount = $this->fake('randomFloat', 2, 1.00, 1000.0);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 302);
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeCreatesAPayment($amount, $address)
    {
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $payment_dao = new models\dao\Payment();

        $this->assertSame(0, $payment_dao->count());

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertSame(1, $payment_dao->count());

        $payment = new models\Payment($payment_dao->take());
        $payment_address = $payment->address();
        $this->assertSame('common_pot', $payment->type);
        $this->assertSame($amount * 100, $payment->amount);
        $this->assertNull($payment->completed_at);
        $this->assertNotNull($payment->payment_intent_id);
        $this->assertSame($address['first_name'], $payment_address['first_name']);
        $this->assertSame($address['last_name'], $payment_address['last_name']);
        $this->assertSame($address['address1'], $payment_address['address1']);
        $this->assertSame($address['postcode'], $payment_address['postcode']);
        $this->assertSame($address['city'], $payment_address['city']);
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeRedirectsIfNoAddress($amount, $address)
    {
        $this->loginUser();

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 302, '/account/address');
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeFailsIfNotConnected($amount, $address)
    {
        $this->create('account', [
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 401);
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeWithInvalidCsrfReturnsABadRequest($amount, $address)
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => 'not the token',
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 400, 'Une vérification de sécurité a échoué');
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeWithoutAcceptingCgvReturnsABadRequest($amount, $address)
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'amount' => $amount,
            'accept_cgv' => false,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Vous devez accepter ces conditions pour participer à la cagnotte.'
        );
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeWithAmountLessThan1ReturnsABadRequest($amount, $address)
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $amount = $this->fake('randomFloat', 2, 0.0, 0.99);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeWithAmountMoreThan1000ReturnsABadRequest($amount, $address)
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $amount = $this->fake('numberBetween', 1001, PHP_INT_MAX / 100);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeWithAmountAsStringReturnsABadRequest($amount, $address)
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);
        $amount = $this->fake('word');

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'amount' => $amount,
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider contributeProvider
     */
    public function testContributeWithMissingAmountReturnsABadRequest($amount, $address)
    {
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('POST', '/account/common-pot/contribute', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'accept_cgv' => true,
        ]);

        $this->assertResponse(
            $response,
            400,
            'Le montant doit être compris entre 1 et 1000 €.',
        );
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUsageRendersCorrectly($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', -42, 7), 'days');
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponse($response, 200, 'Vous êtes sur le point de renouveler un mois');
        $this->assertPointer($response, 'common_pots/usage.phtml');
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUsageRendersIfCommonPotIsNotFullEnough($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', -42, 7), 'days');
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $this->create('common_pot_payment', [
            'amount' => 300,
        ]);

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponse($response, 200, 'il n’y a plus assez d’argent dans la cagnotte');
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUsageRendersIfFreeAccount($address)
    {
        $expired_at = new \DateTime('1970-01-01');
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponse($response, 200, 'Vous bénéficiez déjà d’un compte gratuit');
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUsageRendersNotExpiringSoon($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 8, 42), 'days');
        $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponse(
            $response,
            200,
            'Vous pourrez utiliser la cagnotte lorsque votre abonnement sera sur le point d’expirer'
        );
    }

    public function testUsageRedirectsIfNoAddress()
    {
        $this->loginUser();

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponse($response, 302, '/account/address');
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUsageFailsIfNotConnected($address)
    {
        $this->create('account', [
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
        ]);

        $response = $this->appRun('GET', '/account/common-pot/use');

        $this->assertResponse($response, 401);
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUseExtendsSubscriptionAndRedirectsCorrectly($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', -42, 7), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $account_dao = new models\dao\Account();
        $common_pot_payment_dao = new models\dao\CommonPotPayment();

        $this->assertSame(0, $common_pot_payment_dao->count());

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 302, '/account');
        $this->assertSame(1, $common_pot_payment_dao->count());
        $account = new models\Account($account_dao->find($user['account_id']));
        $this->assertGreaterThan($expired_at->getTimestamp(), $account->expired_at->getTimestamp());
    }

    public function testUseRedirectsIfNoAddress()
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', -42, 7), 'days');
        $user = $this->loginUser([
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $account_dao = new models\dao\Account();
        $common_pot_payment_dao = new models\dao\CommonPotPayment();

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 302, '/account/address');
        $this->assertSame(0, $common_pot_payment_dao->count());
        $account = new models\Account($account_dao->find($user['account_id']));
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUseFailsIfNotConnected($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', -42, 7), 'days');
        $account_id = $this->create('account', [
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $account_dao = new models\dao\Account();
        $common_pot_payment_dao = new models\dao\CommonPotPayment();

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 401);
        $this->assertSame(0, $common_pot_payment_dao->count());
        $account = new models\Account($account_dao->find($account_id));
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUseFailsIfAcceptCgvIsFalse($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', -42, 7), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $account_dao = new models\dao\Account();
        $common_pot_payment_dao = new models\dao\CommonPotPayment();

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'accept_cgv' => false,
        ]);

        $this->assertResponse($response, 400, 'Vous devez accepter ces conditions');
        $this->assertSame(0, $common_pot_payment_dao->count());
        $account = new models\Account($account_dao->find($user['account_id']));
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUseFailsIfCsrfIsInvalid($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', -42, 7), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $account_dao = new models\dao\Account();
        $common_pot_payment_dao = new models\dao\CommonPotPayment();

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => 'not the token',
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 400, 'Une vérification de sécurité a échoué');
        $this->assertSame(0, $common_pot_payment_dao->count());
        $account = new models\Account($account_dao->find($user['account_id']));
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUseFailsIfCommonPotIsNotFullEnough($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', -42, 7), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 200,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $account_dao = new models\dao\Account();
        $common_pot_payment_dao = new models\dao\CommonPotPayment();

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 400, 'La cagnotte n’est pas suffisamment fournie');
        $this->assertSame(0, $common_pot_payment_dao->count());
        $account = new models\Account($account_dao->find($user['account_id']));
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUseFailsIfFreeAccount($address)
    {
        $expired_at = new \DateTime('1970-01-01');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $account_dao = new models\dao\Account();
        $common_pot_payment_dao = new models\dao\CommonPotPayment();

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 400, 'Votre abonnement n’est pas encore prêt d’expirer');
        $this->assertSame(0, $common_pot_payment_dao->count());
        $account = new models\Account($account_dao->find($user['account_id']));
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    /**
     * @dataProvider addressProvider
     */
    public function testUseFailsIfNotExpiringSoon($address)
    {
        $expired_at = \Minz\Time::fromNow($this->fake('numberBetween', 8, 42), 'days');
        $user = $this->loginUser([
            'address_first_name' => $address['first_name'],
            'address_last_name' => $address['last_name'],
            'address_address1' => $address['address1'],
            'address_postcode' => $address['postcode'],
            'address_city' => $address['city'],
            'expired_at' => $expired_at->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        $this->create('payment', [
            'type' => 'common_pot',
            'amount' => 500,
            'completed_at' => $this->fake('dateTime')->getTimestamp(),
        ]);
        $account_dao = new models\dao\Account();
        $common_pot_payment_dao = new models\dao\CommonPotPayment();

        $response = $this->appRun('POST', '/account/common-pot/use', [
            'csrf' => (new \Minz\CSRF())->generateToken(),
            'accept_cgv' => true,
        ]);

        $this->assertResponse($response, 400, 'Votre abonnement n’est pas encore prêt d’expirer');
        $this->assertSame(0, $common_pot_payment_dao->count());
        $account = new models\Account($account_dao->find($user['account_id']));
        $this->assertEquals($account->expired_at->getTimestamp(), $expired_at->getTimestamp());
    }

    public function addressProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'address1' => $faker->streetAddress,
                    'postcode' => $faker->postcode,
                    'city' => $faker->city,
                ],
            ];
        }

        return $datasets;
    }

    public function contributeProvider()
    {
        $faker = \Faker\Factory::create();
        $datasets = [];
        foreach (range(1, \Minz\Configuration::$application['number_of_datasets']) as $n) {
            $datasets[] = [
                $faker->numberBetween(1, 1000),
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'address1' => $faker->streetAddress,
                    'postcode' => $faker->postcode,
                    'city' => $faker->city,
                ],
            ];
        }

        return $datasets;
    }
}
