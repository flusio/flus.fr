<?php

namespace Website;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Accounts
{
    /**
     * @response 401
     *     if the user is not connected
     * @response 200
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function show($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account_dao = new models\dao\Account();
        $db_account = $account_dao->find($user['account_id']);
        $account = new models\Account($db_account);
        return \Minz\Response::ok('accounts/show.phtml', [
            'account' => $account,
            'no_address' => !$account->address_first_name,
            'payments' => $account->payments(),
        ]);
    }

    /**
     * @request_param string account_id
     * @request_param string access_token
     *
     * @response 404
     *     if the account_id does not exist
     * @response 400
     *     if the access_token is invalid
     * @response 302 /account
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function login($request)
    {
        $account_id = $request->param('account_id');
        $access_token = $request->param('access_token');
        $account_dao = new models\dao\Account();
        $token_dao = new models\dao\Token();

        $db_account = $account_dao->find($account_id);
        if (!$db_account) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        $account = new models\Account($db_account);
        if (!$account->checkAccess($access_token)) {
            return \Minz\Response::badRequest('bad_request.phtml');
        }

        utils\CurrentUser::logUserIn($account->id);

        $token_dao->delete($account->access_token);

        return \Minz\Response::redirect('account');
    }

    /**
     * @response 401
     *     if the user is not connected
     * @response 200
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function address($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account_dao = new models\dao\Account();
        $db_account = $account_dao->find($user['account_id']);
        $account = new models\Account($db_account);
        return \Minz\Response::ok('accounts/address.phtml', [
            'account' => $account,
            'email' => $account->email,
            'address' => $account->address(),
            'countries' => utils\Countries::listSorted(),
        ]);
    }

    /**
     * @response 401
     *     if the user is not connected
     * @response 400
     *     if the email or address is invalid
     * @response 302 /account
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function updateAddress($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account_dao = new models\dao\Account();
        $db_account = $account_dao->find($user['account_id']);
        $account = new models\Account($db_account);

        $email = $request->param('email');
        $address = $request->param('address', $account->address());
        $account->email = $email;
        $account->setAddress($address);

        $errors = $account->validate();
        if (!$account->address_first_name) {
            $errors['address_first_name'] = 'Votre prénom est obligatoire.';
        }
        if (!$account->address_last_name) {
            $errors['address_last_name'] = 'Votre nom est obligatoire.';
        }
        if (!$account->address_address1) {
            $errors['address_address1'] = 'Votre adresse est obligatoire.';
        }
        if (!$account->address_postcode) {
            $errors['address_postcode'] = 'Votre code postal est obligatoire.';
        }
        if (!$account->address_city) {
            $errors['address_city'] = 'Votre ville est obligatoire.';
        }

        if ($errors) {
            return \Minz\Response::badRequest('accounts/address.phtml', [
                'account' => $account,
                'email' => $email,
                'address' => $address,
                'countries' => utils\Countries::listSorted(),
                'errors' => $errors,
            ]);
        }

        $csrf = new \Minz\CSRF();
        if (!$csrf->validateToken($request->param('csrf'))) {
            return \Minz\Response::badRequest('accounts/address.phtml', [
                'account' => $account,
                'email' => $email,
                'address' => $address,
                'countries' => utils\Countries::listSorted(),
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        $account_dao->save($account);

        return \Minz\Response::redirect('account');
    }

    /**
     * @request_param string csrf
     * @request_param boolean reminder
     *
     * @response 401
     *     if the user is not connected
     * @response 400
     *     if csrf is invalid
     * @response 302 /account
     *     on success
     *
     * @param \Minz\Request $request
     *
     * @return \Minz\Response
     */
    public function setReminder($request)
    {
        $user = utils\CurrentUser::get();
        if (!$user || utils\CurrentUser::isAdmin()) {
            return \Minz\Response::unauthorized('unauthorized.phtml');
        }

        $account_dao = new models\dao\Account();
        $db_account = $account_dao->find($user['account_id']);
        $account = new models\Account($db_account);

        $reminder = $request->param('reminder', false);

        $csrf = new \Minz\CSRF();
        if ($csrf->validateToken($request->param('csrf'))) {
            $account->reminder = filter_var($reminder, FILTER_VALIDATE_BOOLEAN);
            $account_dao->save($account);
        }

        return \Minz\Response::redirect('account');
    }
}
