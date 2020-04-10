<?php

namespace Website\controllers\home;

use Website\models;

function index()
{
    $payment_dao = new models\dao\Payment();
    $total_revenue = $payment_dao->findTotalRevenue() / 100;
    $revenue_target = 30000;
    $percent_target = min(100, max(5, $total_revenue * 100 / $revenue_target));

    $response = \Minz\Response::ok('home/index.phtml', [
        'total_revenue' => number_format($total_revenue, 2, ',', '&nbsp;'),
        'revenue_target' => number_format($revenue_target, 0, ',', '&nbsp;'),
        'percent_target' => $percent_target,
    ]);
    $response->setContentSecurityPolicy('style-src', "'self' 'unsafe-inline'");
    return $response;
}

function credits()
{
    return \Minz\Response::ok('home/credits.phtml');
}
