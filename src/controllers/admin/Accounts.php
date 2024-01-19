<?php

namespace Website\controllers\admin;

use Minz\Request;
use Minz\Response;
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
     * @response 302 /admin/login
     *     If user is not connected as an admin
     * @response 200
     *     On success
     */
    public function index(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login');
        }

        $accounts = models\Account::listWithCountPayments();

        usort($accounts, function ($account1, $account2) {
            return $account1->email <=> $account2->email;
        });

        return Response::ok('admin/accounts/index.phtml', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Show a specific account for the admin
     *
     * @request_param string id
     *
     * @response 302 /admin/login
     *     If user is not connected as an admin
     * @response 404
     *     If the account doesn't exist
     * @response 200
     *     On success
     */
    public function show(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login');
        }

        $account_id = $request->param('id', '');
        $account = models\Account::find($account_id);
        if (!$account) {
            return Response::notFound('not_found.phtml');
        }

        return Response::ok('admin/accounts/show.phtml', [
            'account' => $account,
            'payments' => $account->payments(),
            'expired_at' => $account->expired_at,
        ]);
    }

    /**
     * Update an account
     *
     * @request_param string id
     * @request_param string csrf
     * @request_param datetime expired-at
     *
     * @response 302 /admin/login
     *     If user is not connected as an admin
     * @response 404
     *     If the account doesn't exist
     * @response 400
     *     If CSRF is invalid
     * @response 200
     *     On success
     */
    public function update(Request $request): Response
    {
        if (!utils\CurrentUser::isAdmin()) {
            return Response::redirect('login');
        }

        $account_id = $request->param('id', '');
        $account = models\Account::find($account_id);
        if (!$account) {
            return Response::notFound('not_found.phtml');
        }

        $csrf = $request->param('csrf', '');
        $expired_at = $request->param('expired-at', '');

        if ($expired_at === '1970-01-01') {
            $expired_at = new \DateTimeImmutable('@0');
        } else {
            $expired_at = \DateTimeImmutable::createFromFormat('Y-m-d', $expired_at);
        }

        if (!$expired_at) {
            return Response::badRequest('admin/accounts/show.phtml', [
                'account' => $account,
                'payments' => $account->payments(),
                'expired_at' => $expired_at,
                'error' => 'Saisissez une date d’expiration au format YYYY-MM-DD',
            ]);
        }

        if (!\Minz\Csrf::validate($csrf)) {
            return Response::badRequest('admin/accounts/show.phtml', [
                'account' => $account,
                'payments' => $account->payments(),
                'expired_at' => $expired_at,
                'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
            ]);
        }

        if ($expired_at->getTimestamp() !== 0) {
            // Be nice, set the expiration date to the end of the day.
            $expired_at = $expired_at->setTime(23, 59, 59);
        }

        $account->expired_at = $expired_at;
        $account->save();

        return Response::redirect('admin account', ['id' => $account->id]);
    }
}
