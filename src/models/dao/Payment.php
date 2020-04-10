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
     * Create a payment if it doesn't exist, or update an existing one
     *
     * @param \Website\models\Payment $model
     *
     * @return integer|boolean Return the id on creation, or true on update.
     */
    public function save($model)
    {
        if ($model->id === null) {
            $values = $model->toValues();
            $values['id'] = bin2hex(random_bytes(16));
            $values['created_at'] = \Minz\Time::now()->getTimestamp();
            return $this->create($values);
        } else {
            $values = $model->toValues();
            $this->update($model->id, $values);
            return true;
        }
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
     * @return integer
     */
    public function findTotalRevenue()
    {
        $sql = 'SELECT SUM(amount) FROM payments '
             . 'WHERE completed_at IS NOT NULL';
        $statement = $this->query($sql);
        return intval($statement->fetchColumn());
    }
}
