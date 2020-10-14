<?php

if (php_sapi_name() !== 'cli') {
    die('This script must be called from command line.');
}

$app_path = realpath(__DIR__ . '/..');

include $app_path . '/autoload.php';

\Minz\Configuration::load('dotenv', $app_path);
\Minz\Environment::initialize();

$account_dao = new \Website\models\dao\Account();
$payment_dao = new \Website\models\dao\Payment();
$db_payments = $payment_dao->listAll();
$database = \Minz\Database::get();

foreach ($db_payments as $db_payment) {
    $payment = new \Website\models\Payment($db_payment);
    if ($payment->account_id) {
        // Payment already has an account_id, skip
        continue;
    }

    $db_account = $account_dao->findBy([
        'email' => $payment->email,
    ]);
    if (!$db_account) {
        // No matching account, skip
        continue;
    }

    $account = new \Website\models\Account($db_account);
    $payment->account_id = $account->id;
    $payment_dao->save($payment);
}
