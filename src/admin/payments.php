<?php

namespace Website\controllers\admin\payments;

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
        return \Minz\Response::redirect('login');
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

    return \Minz\Response::ok('admin/payments/index.phtml', [
        'year' => $year,
        'payments_by_months' => $payments_by_months,
    ]);
}
