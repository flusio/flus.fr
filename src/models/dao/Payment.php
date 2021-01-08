<?php

namespace Website\models\dao;

use Website\models;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Payment extends \Minz\DatabaseModel
{
    use SaveHelper;

    public function __construct()
    {
        $properties = array_keys(models\Payment::PROPERTIES);
        parent::__construct('payments', 'id', $properties);
    }

    /**
     * Return a raw payment (order is not guaranteed)
     *
     * @return array|null
     */
    public function take()
    {
        $all = $this->listAll();
        if (!empty($all)) {
            return $all[0];
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
            AND strftime('%Y', completed_at) = ?
        SQL;
        $statement = $this->prepare($sql);
        $statement->execute([$year]);
        return intval($statement->fetchColumn());
    }

    /**
     * Return the sum of amounts for completed common pot payments
     *
     * @return integer
     */
    public function findCommonPotRevenue()
    {
        $sql = 'SELECT SUM(amount) FROM payments '
             . 'WHERE type = "common_pot" AND completed_at IS NOT NULL';
        $statement = $this->query($sql);
        return intval($statement->fetchColumn());
    }

    /**
     * Return the sum of amounts for completed subscriptions payments
     *
     * @return integer
     */
    public function findSubscriptionsRevenue()
    {
        $sql = 'SELECT SUM(amount) FROM payments '
             . 'WHERE type = "subscription" AND completed_at IS NOT NULL';
        $statement = $this->query($sql);
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
