<?php

namespace Website\models\dao;

use Website\models;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class PotUsage extends \Minz\DatabaseModel
{
    use SaveHelper;

    public function __construct()
    {
        $properties = array_keys(models\PotUsage::PROPERTIES);
        parent::__construct('pot_usages', 'id', $properties);
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
                SELECT COALESCE(SUM(pu.amount), 0)
                FROM pot_usages pu
                WHERE pu.completed_at IS NOT NULL
            )
            FROM payments p
            WHERE p.type = "common_pot"
            AND p.completed_at IS NOT NULL
            AND p.id NOT IN (
                SELECT p2.credited_payment_id FROM payments p2
                WHERE p2.credited_payment_id NOT NULL
                AND p2.completed_at IS NOT NULL
            )
        SQL;
        $statement = $this->query($sql);
        return intval($statement->fetchColumn());
    }
}
