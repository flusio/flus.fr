<?php

namespace Website\models\dao;

/**
 * @author  Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Account extends \Minz\DatabaseModel
{
    /**
     * @throws \Minz\Errors\DatabaseError
     */
    public function __construct()
    {
        $properties = array_keys(\Website\models\Account::PROPERTIES);
        parent::__construct('accounts', 'id', $properties);
    }

    /**
     * Return the list of accounts with computed count_payments
     *
     * @return array[]
     */
    public function listWithCountPayments()
    {
        $sql = <<<SQL
            SELECT a.*, (
                SELECT COUNT(p.id) FROM payments p
                WHERE p.account_id = a.id
            ) AS count_payments
            FROM accounts a
        SQL;

        $statement = $this->query($sql);
        return $statement->fetchAll();
    }
}
