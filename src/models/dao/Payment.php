<?php

namespace Website\models\dao;

use Website\models;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Payment extends \Minz\DatabaseModel
{
    public function __construct()
    {
        $properties = array_keys(models\Payment::PROPERTIES);
        parent::__construct('payments', 'id', $properties);
    }

    /**
     * Return an ongoing payment for the given account
     *
     * @param string $account_id
     *
     * @return array|null
     */
    public function findOngoingForAccount($account_id)
    {
        $sql = 'SELECT * FROM payments '
             . 'WHERE account_id = ? '
             . 'AND completed_at IS NULL '
             . 'LIMIT 1';

        $statement = $this->prepare($sql);
        $statement->execute([$account_id]);
        $result = $statement->fetch();
        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * Return the last invoice number saved in the database
     *
     * @return string
     */
    public function findLastInvoiceNumber()
    {
        $sql = 'SELECT invoice_number FROM payments '
             . 'WHERE invoice_number IS NOT NULL '
             . 'ORDER BY invoice_number DESC '
             . 'LIMIT 1';
        $statement = $this->query($sql);
        return $statement->fetchColumn();
    }

    /**
     * Return the sum of amounts for completed payments
     *
     * @param integer $year
     *
     * @return integer
     */
    public function findTotalRevenue($year)
    {
        $sql = <<<'SQL'
            SELECT SUM(amount) FROM payments
            WHERE completed_at IS NOT NULL
            AND type != 'credit'
            AND id NOT IN (
                SELECT p2.credited_payment_id FROM payments p2
                WHERE p2.type = 'credit'
            )
            AND strftime('%Y', completed_at) = ?
        SQL;
        $statement = $this->prepare($sql);
        $statement->execute([$year]);
        return intval($statement->fetchColumn());
    }

    /**
     * Return the sum of amounts for completed common pot payments
     *
     * @param integer $year
     *
     * @return integer
     */
    public function findCommonPotRevenue($year)
    {
        $sql = <<<'SQL'
            SELECT SUM(amount) FROM payments
            WHERE type = "common_pot"
            AND completed_at IS NOT NULL
            AND id NOT IN (
                SELECT p2.credited_payment_id FROM payments p2
                WHERE p2.type = 'credit'
            )
            AND strftime('%Y', completed_at) = ?
        SQL;
        $statement = $this->prepare($sql);
        $statement->execute([$year]);
        return intval($statement->fetchColumn());
    }

    /**
     * Return the sum of amounts for completed subscriptions payments
     *
     * @param integer $year
     *
     * @return integer
     */
    public function findSubscriptionsRevenue($year)
    {
        $sql = <<<'SQL'
            SELECT SUM(amount) FROM payments
            WHERE type = "subscription"
            AND completed_at IS NOT NULL
            AND id NOT IN (
                SELECT p2.credited_payment_id FROM payments p2
                WHERE p2.type = 'credit'
            )
            AND strftime('%Y', completed_at) = ?
        SQL;
        $statement = $this->prepare($sql);
        $statement->execute([$year]);
        return intval($statement->fetchColumn());
    }

    /**
     * Return the payments for a given year
     *
     * @param integer $year
     *
     * @return array
     */
    public function listByYear($year)
    {
        $sql = 'SELECT * FROM payments '
             . 'WHERE strftime("%Y", datetime(created_at)) = ? '
             . 'ORDER BY created_at DESC';
        $statement = $this->prepare($sql);
        $statement->execute([$year]);
        return $statement->fetchAll();
    }
}
