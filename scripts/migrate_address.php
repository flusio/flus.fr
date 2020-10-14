<?php

if (php_sapi_name() !== 'cli') {
    die('This script must be called from command line.');
}

$app_path = realpath(__DIR__ . '/..');

include $app_path . '/autoload.php';

\Minz\Configuration::load('dotenv', $app_path);
\Minz\Environment::initialize();

$account_dao = new \Website\models\dao\Account();
$db_accounts = $account_dao->listAll();
$database = \Minz\Database::get();

foreach ($db_accounts as $db_account) {
    $account = new \Website\models\Account($db_account);
    if ($account->address_first_name) {
        // Account already has an address, skip
        continue;
    }

    $statement = $database->prepare(<<<'SQL'
        SELECT * FROM payments
        WHERE email = ?
        ORDER BY created_at DESC
        LIMIT 1
    SQL);
    $statement->execute([$account->email]);
    $db_payment = $statement->fetch();
    if (!$db_payment) {
        // No matching payments, skip
        continue;
    }

    $account->address_first_name = $db_payment['address_first_name'];
    $account->address_last_name = $db_payment['address_last_name'];
    $account->address_address1 = $db_payment['address_address1'];
    $account->address_postcode = $db_payment['address_postcode'];
    $account->address_city = $db_payment['address_city'];
    $account->address_country = $db_payment['address_country'];
    $account_dao->save($account);
}
