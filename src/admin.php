<?php

namespace Website\controllers\admin;

use Website\utils;
use Website\models;

/**
 * Show the admin main page
 *
 * @return \Minz\Response
 */
function index()
{
    $current_user = utils\currentUser();
    if (!$current_user) {
        return \Minz\Response::redirect('admin#login');
    }

    $year = \Minz\Time::now()->format('Y');
    $payment_dao = new models\dao\Payment();
    $raw_payments = $payment_dao->listByYear($year);
    $payments_by_months = [];
    foreach ($raw_payments as $raw_payment) {
        $payment = new models\Payment($raw_payment);
        $month = intval($payment->created_at->format('n'));
        $payments_by_months[$month][] = $payment;
    }

    return \Minz\Response::ok('admin/index.phtml', [
        'year' => $year,
        'payments_by_months' => $payments_by_months,
    ]);
}

/**
 * Show the admin login page
 *
 * Parameter is:
 *
 * - `from` (optional): allow to redirect to the given action pointer instead
 *   of the admin main page
 *
 * @param \Minz\Request $request
 *
 * @return \Minz\Response
 */
function login($request)
{
    if (utils\currentUser()) {
        return \Minz\Response::redirect('admin#index');
    }

    return \Minz\Response::ok('admin/login.phtml', [
        'from' => $request->param('from'),
    ]);
}

/**
 * Create a session for the user who tries to log in
 *
 * Parameters are:
 *
 * - `csrf`
 * - `password`
 * - `from` (optional)
 *
 * If the password is good, user is redirected to the admin main page, or to
 * the `from` page.
 *
 * @param \Minz\Request $request
 *
 * @return \Minz\Response
 */
function create_session($request)
{
    if (utils\currentUser()) {
        return \Minz\Response::redirect('admin#index');
    }

    $password = $request->param('password');
    $from = $request->param('from');

    $csrf = new \Minz\CSRF();
    if (!$csrf->validateToken($request->param('csrf'))) {
        return \Minz\Response::badRequest('admin/login.phtml', [
            'from' => $from,
            'error' => 'Une vérification de sécurité a échoué, veuillez réessayer de soumettre le formulaire.',
        ]);
    }

    $hash = \Minz\Configuration::$application['admin_secret'];
    if (\password_verify($password, $hash)) {
        $_SESSION['connected'] = true;

        if ($from) {
            $location = urldecode($from);
        } else {
            $location = 'admin#index';
        }
        return \Minz\Response::redirect($location, ['status' => 'connected']);
    } else {
        return \Minz\Response::badRequest('admin/login.phtml', [
            'from' => $from,
            'error' => 'Le mot de passe semble invalide, désolé.',
        ]);
    }
}

/**
 * Delete a session and log out the user
 *
 * Parameter is:
 *
 * - `csrf`
 *
 * @param \Minz\Request $request
 *
 * @return \Minz\Response Always redirect to the home page
 */
function delete_session($request)
{
    $csrf = new \Minz\CSRF();
    if ($csrf->validateToken($request->param('csrf')) && utils\currentUser()) {
        session_unset();
    }

    return \Minz\Response::redirect('home#index');
}
