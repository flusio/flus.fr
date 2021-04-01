<?php

namespace Website\admin;

use Website\models;
use Website\utils;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Accounts
{
    /**
     * List accounts for the admin
     *
     * @return \Minz\Response
     */
    public function index()
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login');
        }

        $accounts = models\Account::listAll();

        usort($accounts, function ($account1, $account2) {
            return $account1->email <=> $account2->email;
        });

        return \Minz\Response::ok('admin/accounts/index.phtml', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Show a specific account for the admin
     *
     * @return \Minz\Response
     */
    public function show($request)
    {
        if (!utils\CurrentUser::isAdmin()) {
            return \Minz\Response::redirect('login');
        }

        $account_id = $request->param('id');
        $account = models\Account::find($account_id);
        if (!$account) {
            return \Minz\Response::notFound('not_found.phtml');
        }

        return \Minz\Response::ok('admin/accounts/show.phtml', [
            'account' => $account,
            'payments' => $account->payments(),
        ]);
    }
}
