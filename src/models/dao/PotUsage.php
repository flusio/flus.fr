<?php

namespace Website\models\dao;

use Website\models;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class PotUsage extends \Minz\DatabaseModel
{
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

    /**
     * Change account_id of the given pot_usages
     *
     * @param string[] $pot_usages_ids
     * @param string $account_id
     *
     * @return boolean
     */
    public function moveToAccountId($pot_usages_ids, $account_id)
    {
        $question_marks = array_fill(0, count($pot_usages_ids), '?');
        $in_statement = implode(',', $question_marks);
        $sql = <<<SQL
            UPDATE pot_usages
            SET account_id = ?
            WHERE id IN ({$in_statement})
        SQL;

        $statement = $this->prepare($sql);
        $parameters = [$account_id];
        $parameters = array_merge($parameters, $pot_usages_ids);
        return $statement->execute($parameters);
    }
}
