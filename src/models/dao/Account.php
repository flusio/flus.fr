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

    /**
     * Update the last_sync_at of the given accounts.
     *
     * @param string[] $account_ids
     * @param \DateTime $date
     *
     * @return boolean True on success or false on failure
     */
    public function updateLastSyncAt($account_ids, $date)
    {
        $question_marks = array_fill(0, count($account_ids), '?');
        $in_statement = implode(',', $question_marks);

        $sql = <<<SQL
            UPDATE accounts
            SET last_sync_at = ?
            WHERE id IN ({$in_statement})
        SQL;

        $statement = $this->prepare($sql);
        $parameters = [
            $date->format(\Minz\Model::DATETIME_FORMAT),
        ];
        $parameters = array_merge($parameters, $account_ids);
        return $statement->execute($parameters);
    }

    /**
     * List the accounts which have a last_sync_at property older than the
     * given date.
     *
     * @param \DateTime $date
     *
     * @return array
     */
    public function listByLastSyncAtOlderThan($date)
    {
        $sql = <<<SQL
            SELECT * FROM accounts
            WHERE last_sync_at < ? OR last_sync_at IS NULL
        SQL;

        $statement = $this->prepare($sql);
        $statement->execute([
            $date->format(\Minz\Model::DATETIME_FORMAT),
        ]);
        return $statement->fetchAll();
    }
}
