<?php

namespace Website;

class CommonPots
{
    /**
     * Show the page about the common pot.
     *
     * @response 200
     */
    public function show()
    {
        $payment_dao = new models\dao\Payment();
        $common_pot_amount = $payment_dao->findCommonPotRevenue() / 100;
        $available_accounts = floor($common_pot_amount / 3);
        return \Minz\Response::ok('common_pots/show.phtml', [
            'common_pot_amount' => number_format($common_pot_amount, 2, ',', '&nbsp;'),
            'available_accounts' => number_format($available_accounts, 0, ',', '&nbsp;'),
        ]);
    }

    /**
     * Show the page allowing to contribute to the common pot
     *
     * @response 401
     *     if the user is not connected
     * @response 302 /account/address
     *     if the address is not set
     * @response 200
     *     on success
     */
    public function contribution()
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account_dao = new models\dao\Account();
        $db_account = $account_dao->find($user['account_id']);
        $account = new models\Account($db_account);

        $no_address = !$account->address_first_name;
        if ($no_address) {
            return \Minz\Response::redirect('account address');
        }

        return \Minz\Response::ok('common_pots/contribution.phtml', [
            'account' => $account,
            'amount' => 30,
        ]);
    }

    /**
     * Handle the payment request for the common pot contribution and redirect to Stripe.
     *
     * @request_param string csrf
     * @request_param integer amount must be between 1 and 1000
     * @request_param boolean accept_cgv
     *
     * @response 401
     *     if the user is not connected
     * @response 302 /account/address
     *     if the address is not set
     * @response 400
     *     if accept_cgv is false or if CSRF or amount are invalid
     * @response 302 /payments/:id/pay
     *     on success
     */
    public function contribute($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account_dao = new models\dao\Account();
        $db_account = $account_dao->find($user['account_id']);
        $account = new models\Account($db_account);

        $no_address = !$account->address_first_name;
        if ($no_address) {
            return \Minz\Response::redirect('account address');
        }

        $accept_cgv = $request->param('accept_cgv', false);
        $amount = $request->param('amount', 0);

        if (!$accept_cgv) {
            return \Minz\Response::badRequest('common_pots/contribution.phtml', [
                'account' => $account,
                'amount' => $amount,
                'errors' => [
                    'cgv' => 'Vous devez accepter ces conditions pour participer à la cagnotte.',
                ],
            ]);
        }

        $payment = models\Payment::initCommonPotFromAccount($account, $amount);
        $errors = $payment->validate();
        if ($errors) {
            return \Minz\Response::badRequest('common_pots/contribution.phtml', [
                'account' => $account,
                'amount' => $amount,
                'errors' => $errors,
            ]);
        }

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
            return \Minz\Response::badRequest('common_pots/contribution.phtml', [
                'account' => $account,
                'amount' => $amount,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $stripe_service = new services\Stripe(
            \Minz\Url::absoluteFor('Payments#succeeded'),
            \Minz\Url::absoluteFor('Payments#canceled')
        );

        $stripe_session = $stripe_service->createSession(
            $payment,
            'Participation à la cagnotte de Flus'
        );

        $payment->payment_intent_id = $stripe_session->payment_intent;
        $payment->session_id = $stripe_session->id;
        $payment_id = $payment_dao->save($payment);

        return \Minz\Response::redirect('Payments#pay', [
            'id' => $payment_id,
        ]);
    }
}
