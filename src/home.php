<?php

namespace Website\controllers\home;

use Website\models;

function index()
{
    if (!\Minz\Configuration::$application['enabled']) {
        return \Minz\Response::ok('maintenance.phtml');
    }

    $payment_dao = new models\dao\Payment();
    $total_revenue = $payment_dao->findTotalRevenue() / 100;
    $revenue_target = 30000;
    $percent_target = min(100, max(5, $total_revenue * 100 / $revenue_target));

    $response = \Minz\Response::ok('home/index.phtml', [
        'total_revenue' => number_format($total_revenue, 2, ',', '&nbsp;'),
        'revenue_target' => number_format($revenue_target, 0, ',', '&nbsp;'),
        'percent_target' => intval($percent_target),
    ]);
    $response->setContentSecurityPolicy('style-src', "'self' 'unsafe-inline'");
    return $response;
}

function funding()
{
    $payment_dao = new models\dao\Payment();
    $total_revenue = $payment_dao->findTotalRevenue() / 100;
    $revenue_target = 30000;
    $percent_target = min(100, max(5, $total_revenue * 100 / $revenue_target));
    $common_pot_amount = $payment_dao->findCommonPotRevenue() / 100;
    $subscriptions_amount = $payment_dao->findSubscriptionsRevenue() / 100;

    $response = \Minz\Response::ok('home/funding.phtml', [
        'total_revenue' => number_format($total_revenue, 2, ',', '&nbsp;'),
        'revenue_target' => number_format($revenue_target, 0, ',', '&nbsp;'),
        'percent_target' => intval($percent_target),
        'common_pot_amount' => number_format($common_pot_amount, 2, ',', '&nbsp;'),
        'subscriptions_amount' => number_format($subscriptions_amount, 2, ',', '&nbsp;'),
    ]);
    $response->setContentSecurityPolicy('style-src', "'self' 'unsafe-inline'");
    return $response;
}

function credits()
{
    return \Minz\Response::ok('home/credits.phtml');
}

function legal()
{
    return \Minz\Response::ok('home/legal.phtml');
}

function cgv()
{
    return \Minz\Response::ok('home/cgv.phtml');
}
