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
}
