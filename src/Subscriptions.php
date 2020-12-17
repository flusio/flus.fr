<?php

namespace Website;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Subscriptions
{
    /**
     * @response 401
     *     if the user is not connected
     * @response 302 /account/address
     *     if the address is not set
     * @response 200
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function init($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account_dao = new models\dao\Account();
        $db_account = $account_dao->find($user['account_id']);
        $account = new models\Account($db_account);

        if ($account->mustSetAddress()) {
            return \Minz\Response::redirect('account address');
        }

        return \Minz\Response::ok('subscriptions/init.phtml', [
            'account' => $account,
        ]);
    }

    /**
     * Initialize a subscription Payment for the current account and call
     * Stripe API to start a payment session.
     *
     * @request_param string csrf
     * @request_param string frequency (month or year)
     *
     * @response 401
     *     if the user is not connected
     * @response 302 /account/address
     *     if the address is not set
     * @response 400
     *     if CSRF or frequency are invalid
     * @response 302 /payments/:id/pay
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function renew($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account_dao = new models\dao\Account();
        $db_account = $account_dao->find($user['account_id']);
        $account = new models\Account($db_account);

        if ($account->mustSetAddress()) {
            return \Minz\Response::redirect('account address');
        }

        $frequency = $request->param('frequency');
        $payment = models\Payment::initSubscriptionFromAccount($account, $frequency);
        $errors = $payment->validate();
        if ($errors) {
            return \Minz\Response::badRequest('subscriptions/init.phtml', [
                'account' => $account,
                'errors' => $errors,
            ]);
        }

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
            return \Minz\Response::badRequest('subscriptions/init.phtml', [
                'account' => $account,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $stripe_service = new services\Stripe(
            $request->param('success_url', \Minz\Url::absoluteFor('Payments#succeeded')),
            $request->param('cancel_url', \Minz\Url::absoluteFor('Payments#canceled'))
        );

        $period = $payment->frequency === 'month' ? '1 mois' : '1 an';
        $stripe_session = $stripe_service->createSession(
            $payment,
            "Abonnement à Flus ({$period})"
        );

        $payment_dao = new models\dao\Payment();
        $payment->payment_intent_id = $stripe_session->payment_intent;
        $payment->session_id = $stripe_session->id;
        $payment_id = $payment_dao->save($payment);

        $account->preferred_frequency = $payment->frequency;
        $account_dao->save($account);

        return \Minz\Response::redirect('Payments#pay', [
            'id' => $payment_id,
        ]);
    }
}
