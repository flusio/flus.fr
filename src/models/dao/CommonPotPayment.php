<?php

namespace Website\models\dao;

use Website\models;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class CommonPotPayment extends \Minz\DatabaseModel
{
    use SaveHelper;

    public function __construct()
    {
        $properties = array_keys(models\CommonPotPayment::PROPERTIES);
        parent::__construct('common_pot_payments', 'id', $properties);
    }

    /**
     * Return the amount actually available in the common pot
     *
     * @return integer
     */
    public function findAvailableAmount()
    {
        $sql = <<<'SQL'
            SELECT COALESCE(SUM(p.amount), 0) - (
                SELECT COALESCE(SUM(cpp.amount), 0)
                FROM common_pot_payments cpp
                WHERE cpp.completed_at IS NOT NULL
            )
            FROM payments p
            WHERE p.type = "common_pot" AND p.completed_at IS NOT NULL
        SQL;
        $statement = $this->query($sql);
        return intval($statement->fetchColumn());
    }
}
