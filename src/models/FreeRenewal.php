<?php

namespace Website\models;

use Minz\Database;

/**
 * Help to keep the history of the free renewals without tracing users.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
#[Database\Table(name: 'free_renewals')]
class FreeRenewal
{
    use Database\Recordable;

    #[Database\Column]
    public int $id;

    #[Database\Column]
    public \DateTimeImmutable $created_at;

    #[Database\Column]
    public int $quantity;

    public function __construct(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Return the number of FreeRenewals per months for a given year.
     *
     * @return array<string, int>
     */
    public static function countPerMonth(int $year): array
    {
        $sql = <<<SQL
            SELECT
                strftime("%m", datetime(created_at)) AS month,
                SUM(quantity) AS count
            FROM free_renewals
            WHERE strftime("%Y", datetime(created_at)) = :year
            GROUP BY month
            SQL;

        $database = \Minz\Database::get();
        $statement = $database->prepare($sql);
        $statement->execute([
            ':year' => $year,
        ]);
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
