<?php

namespace Website\models;

use Minz\Database;
use Minz\Validable;
use Website\utils;

/**
 * A Payment represents a payment by a customer. It allows easy manipulations
 * from Stripe service to the database.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
#[Database\Table(name: 'payments')]
class Payment
{
    use Database\Recordable;
    use Validable;

    public const MIN_AMOUNT = 1 * 100;
    public const MAX_AMOUNT = 120 * 100;

    #[Database\Column]
    public string $id;

    #[Database\Column]
    public \DateTimeImmutable $created_at;

    #[Database\Column]
    public ?\DateTimeImmutable $completed_at = null;

    #[Database\Column]
    public bool $is_paid = false;

    #[Validable\Format(
        pattern: '/^[1-9][0-9]{3}-[0-9]{2}-[0-9]{4}$/',
        message: 'La génération du numéro de facture a échoué.'
    )]
    #[Database\Column]
    public ?string $invoice_number = null;

    #[Validable\Inclusion(
        in: ['common_pot', 'subscription', 'credit'],
        message: 'La génération du type de paiement a échoué.'
    )]
    #[Database\Column]
    public string $type;

    #[Validable\Comparison(
        greater_or_equal: Payment::MIN_AMOUNT,
        less_or_equal: Payment::MAX_AMOUNT,
        message: 'Le montant doit être compris entre 1 et 120 €.',
    )]
    #[Database\Column]
    public int $amount;

    #[Database\Column]
    public int $quantity;

    #[Validable\Length(min: 1, message: 'La génération du paiement Stripe a échoué.')]
    #[Database\Column]
    public ?string $payment_intent_id = null;

    #[Validable\Length(min: 1, message: 'La génération du paiement Stripe a échoué.')]
    #[Database\Column]
    public ?string $session_id = null;

    #[Validable\Inclusion(in: ['month', 'year'], message: 'Vous devez choisir l’une des deux périodes proposées.')]
    #[Database\Column]
    public ?string $frequency = null;

    #[Database\Column]
    public ?string $credited_payment_id = null;

    #[Database\Column]
    public string $account_id;

    /**
     * Initialize a Payment object from user request parameters.
     *
     * Amount is always in cents.
     */
    private function __construct(string $type, int $amount)
    {
        $this->id = \Minz\Random::hex(32);
        $this->type = $type;
        $this->amount = $amount;
        $this->quantity = 1;
        $this->is_paid = false;
    }

    /**
     * Init a subscription payment from an account.
     *
     * @param integer|float $euros
     */
    public static function initSubscriptionFromAccount(Account $account, mixed $euros): self
    {
        $amount = intval($euros * 100);

        $payment = new self('subscription', $amount);
        $payment->frequency = 'year';
        $payment->account_id = $account->id;
        $payment->quantity = $account->countManagedAccounts() + 1;

        return $payment;
    }

    /**
     * Init a credit payment from a payment.
     */
    public static function initCreditFromPayment(self $payment): self
    {
        $credit = new self('credit', $payment->totalAmount());
        $credit->account_id = $payment->account_id;
        $credit->credited_payment_id = $payment->id;

        return $credit;
    }

    /**
     * Return the total amount of the payment.
     */
    public function totalAmount(): int
    {
        return $this->amount * $this->quantity;
    }

    /**
     * Return the account associated to the payment if any
     */
    public function account(): ?Account
    {
        if (!$this->account_id) {
            return null;
        }

        return Account::find($this->account_id);
    }

    public function invoiceFilepath(): ?string
    {
        if (!$this->invoice_number) {
            return null;
        }

        $invoices_path = \Minz\Configuration::$data_path . '/invoices';
        return $invoices_path . '/' . $this->invoiceFilename();
    }

    public function invoiceFilename(): ?string
    {
        if (!$this->invoice_number) {
            return null;
        }

        return "facture_{$this->invoice_number}.pdf";
    }

    public function invoiceExists(): bool
    {
        $invoice_filepath = $this->invoiceFilepath();

        if (!$invoice_filepath) {
            return false;
        }

        return file_exists($invoice_filepath);
    }

    public function stripeFees(): int
    {
        if ($this->payment_intent_id) {
            return intval(floor($this->amount * 0.014) + 25);
        } else {
            return 0;
        }
    }

    public function isReimbursed(): bool
    {
        return self::existsBy([
            'credited_payment_id' => $this->id,
        ]);
    }

    /**
     * Mark the payment as completed
     */
    public function complete(\DateTimeImmutable $completed_at): void
    {
        if ($this->is_paid) {
            $this->completed_at = $completed_at;
            if (!$this->invoice_number) {
                $this->invoice_number = self::generateInvoiceNumber();
            }
        }
    }

    public static function generateInvoiceNumber(): string
    {
        $now = \Minz\Time::now();

        $last_invoice_number = self::findLastInvoiceNumber();
        if ($last_invoice_number) {
            list(
                $last_invoice_year,
                $last_invoice_month,
                $last_invoice_sequence
            ) = array_map('intval', explode('-', $last_invoice_number));

            $year = intval($now->format('Y'));
            if ($last_invoice_year === $year) {
                $invoice_sequence = $last_invoice_sequence + 1;
            } else {
                $invoice_sequence = 1;
            }
        } else {
            $invoice_sequence = 1;
        }

        return $now->format('Y-m') . sprintf('-%04d', $invoice_sequence);
    }

    /**
     * Return an ongoing payment for the given account
     */
    public static function findOngoingForAccount(string $account_id): ?self
    {
        $sql = 'SELECT * FROM payments '
             . 'WHERE account_id = ? '
             . 'AND completed_at IS NULL '
             . 'LIMIT 1';

        $database = \Minz\Database::get();
        $statement = $database->prepare($sql);
        $statement->execute([$account_id]);

        $result = $statement->fetch();
        if (is_array($result)) {
            return self::fromDatabaseRow($result);
        } else {
            return null;
        }
    }

    /**
     * Return the last invoice number saved in the database
     */
    public static function findLastInvoiceNumber(): ?string
    {
        $sql = 'SELECT invoice_number FROM payments '
             . 'WHERE invoice_number IS NOT NULL '
             . 'ORDER BY invoice_number DESC '
             . 'LIMIT 1';

        $database = \Minz\Database::get();
        $statement = $database->query($sql);

        $invoice_number = $statement->fetchColumn();
        if (is_string($invoice_number) && $invoice_number) {
            return $invoice_number;
        } else {
            return null;
        }
    }

    /**
     * Return the sum of amounts for completed payments
     */
    public static function findTotalRevenue(int $year): int
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

        $database = \Minz\Database::get();
        $statement = $database->prepare($sql);
        $statement->execute([$year]);
        return intval($statement->fetchColumn());
    }

    /**
     * Return the sum of amounts for completed common pot payments
     */
    public static function findCommonPotRevenue(int $year): int
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

        $database = \Minz\Database::get();
        $statement = $database->prepare($sql);
        $statement->execute([$year]);
        return intval($statement->fetchColumn());
    }

    /**
     * Return the sum of amounts for completed subscriptions payments
     */
    public static function findSubscriptionsRevenue(int $year): int
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

        $database = \Minz\Database::get();
        $statement = $database->prepare($sql);
        $statement->execute([$year]);
        return intval($statement->fetchColumn());
    }

    /**
     * Return the payments for a given year
     *
     * @return self[]
     */
    public static function listByYear(int $year): array
    {
        $sql = 'SELECT * FROM payments '
             . 'WHERE strftime("%Y", datetime(created_at)) = ? '
             . 'ORDER BY created_at DESC';

        $database = \Minz\Database::get();
        $statement = $database->prepare($sql);
        $statement->execute([$year]);
        return self::fromDatabaseRows($statement->fetchAll());
    }

    /**
     * Change account_id of the given payments
     *
     * @param string[] $payments_ids
     */
    public static function moveToAccountId(array $payments_ids, string $account_id): bool
    {
        $question_marks = array_fill(0, count($payments_ids), '?');
        $in_statement = implode(',', $question_marks);
        $sql = <<<SQL
            UPDATE payments
            SET account_id = ?
            WHERE id IN ({$in_statement})
        SQL;

        $database = \Minz\Database::get();
        $statement = $database->prepare($sql);
        $parameters = [$account_id];
        $parameters = array_merge($parameters, $payments_ids);
        return $statement->execute($parameters);
    }

    /**
     * Return the contribution price in euros.
     *
     * The contribution price is defined as the price which all the active
     * accounts + the current person should have paid in order to achieve the
     * financial goal.
     */
    public static function contributionPrice(): int
    {
        $financial_goal = \Minz\Configuration::$application['financial_goal'];

        // Consider the person who is going to subscribe.
        $active_accounts = Account::countActive() + 1;

        $min_price = self::MIN_AMOUNT / 100;
        $max_price = self::MAX_AMOUNT / 100;

        $price = intval($financial_goal / $active_accounts);

        return max($min_price, min($max_price, $price));
    }
}
