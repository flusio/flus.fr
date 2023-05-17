<?php

namespace Website\models;

use Minz\Database;
use Minz\Validable;
use Website\utils;

/**
 * @phpstan-import-type CountryCode from utils\Countries
 *
 * @phpstan-type AccountAddress array{
 *     'first_name': string,
 *     'last_name': string,
 *     'address1': string,
 *     'postcode': string,
 *     'city': string,
 *     'country': CountryCode,
 * }
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
#[Database\Table(name: 'accounts')]
class Account
{
    use Database\Recordable;
    use Validable;

    #[Database\Column]
    public string $id;

    #[Database\Column]
    public \DateTimeImmutable $created_at;

    #[Database\Column]
    public \DateTimeImmutable $expired_at;

    #[Validable\Presence(message: 'Saisissez une adresse courriel.')]
    #[Validable\Email(message: 'Saisissez une adresse courriel valide.')]
    #[Database\Column]
    public string $email;

    #[Database\Column]
    public ?string $access_token = null;

    #[Database\Column]
    public ?\DateTimeImmutable $last_sync_at = null;

    #[Validable\Inclusion(in: ['month', 'year'], message: 'Saisissez une fréquence valide.')]
    #[Database\Column]
    public string $preferred_frequency;

    #[Validable\Inclusion(in: ['common_pot', 'card'], message: 'Saisissez un mode de paiement valide.')]
    #[Database\Column]
    public string $preferred_payment_type;

    #[Validable\Inclusion(in: ['flusio', 'freshrss'], message: 'Saisissez un service valide.')]
    #[Database\Column]
    public string $preferred_service;

    #[Database\Column]
    public bool $reminder;

    #[Database\Column]
    public ?string $address_first_name;

    #[Database\Column]
    public ?string $address_last_name;

    #[Database\Column]
    public ?string $address_address1;

    #[Database\Column]
    public ?string $address_postcode;

    #[Database\Column]
    public ?string $address_city;

    /** @var ?CountryCode */
    #[Validable\Inclusion(
        in: utils\Countries::COUNTRIES,
        mode: 'keys',
        message: 'Saisissez un pays de la liste.'
    )]
    #[Database\Column]
    public ?string $address_country;

    // What a tremendous verification! This could be improved, but I don't
    // plan to let anyone to set its vat number himself, so this is fine
    // for now.
    #[Validable\Length(min: 10, max: 20, message: 'Saisissez un numéro de TVA valide.')]
    #[Database\Column]
    public ?string $company_vat_number = null;

    #[Database\Column(computed: true)]
    public int $count_payments;

    public function __construct(string $email)
    {
        $this->id = \Minz\Random::hex(32);
        $this->email = \Minz\Email::sanitize($email);
        $this->expired_at = \Minz\Time::fromNow(1, 'month');
        $this->preferred_frequency = 'year';
        $this->preferred_payment_type = 'card';
        $this->preferred_service = 'flusio';
        $this->reminder = true;
        $this->address_country = 'FR';
        $this->last_sync_at = \Minz\Time::now();
    }

    /**
     * Return the default account (to rattach payments and pot_usages of
     * deleted accounts).
     *
     * last_sync_at is updated each time the method is called.
     */
    public static function defaultAccount(): self
    {
        $email = \Minz\Configuration::$application['support_email'];

        $account = self::findBy(['email' => $email]);
        if (!$account) {
            $account = new self($email);
            $account->expired_at = new \DateTimeImmutable('@0');
            $account->reminder = false;
        }

        $account->last_sync_at = \Minz\Time::now();
        $account->save();

        return $account;
    }

    /**
     * Extend the subscription period by 1 year
     */
    public function extendSubscription(): void
    {
        if ($this->isFree()) {
            // Free accounts don't need to be extended
            return;
        }

        $today = \Minz\Time::now();
        $latest_date = max($today, $this->expired_at);
        $this->expired_at = $latest_date->modify('+1 year');
    }

    public function checkAccess(string $access_token): bool
    {
        if (!$this->access_token || !$access_token) {
            return false;
        }

        $equals = hash_equals($this->access_token, $access_token);
        if (!$equals) {
            return false;
        }

        $token = Token::find($this->access_token);
        if (!$token) {
            return false;
        }

        return $token->isValid();
    }

    /**
     * Return the address information as an array
     *
     * @return AccountAddress
     */
    public function address()
    {
        return [
            'first_name' => $this->address_first_name ?? '',
            'last_name' => $this->address_last_name ?? '',
            'address1' => $this->address_address1 ?? '',
            'postcode' => $this->address_postcode ?? '',
            'city' => $this->address_city ?? '',
            'country' => $this->address_country ?? 'FR',
        ];
    }

    /**
     * @param array{
     *     'first_name'?: string,
     *     'last_name'?: string,
     *     'address1'?: string,
     *     'postcode'?: string,
     *     'city'?: string,
     *     'country'?: CountryCode,
     * } $address
     */
    public function setAddress(array $address): void
    {
        $this->address_first_name = trim($address['first_name'] ?? '');
        $this->address_last_name = trim($address['last_name'] ?? '');
        $this->address_address1 = trim($address['address1'] ?? '');
        $this->address_postcode = trim($address['postcode'] ?? '');
        $this->address_city = trim($address['city'] ?? '');
        $this->address_country = $address['country'] ?? 'FR';
    }

    /**
     * Return whether the user needs to set its address or not.
     */
    public function mustSetAddress(): bool
    {
        return !$this->address_first_name;
    }

    /**
     * Return whether the account has a free subscription or not
     */
    public function isFree(): bool
    {
        return $this->expired_at->getTimestamp() === 0;
    }

    /**
     * Return whether the subscription has expired or not
     */
    public function hasExpired(): bool
    {
        return !$this->isFree() && $this->expired_at <= \Minz\Time::now();
    }

    /**
     * Return whether the account is sync or not.
     *
     * If the account is not sync, it probably means the user deleted its
     * account on the connected services (i.e. flusio and/or FreshRSS).
     */
    public function isSync(): bool
    {
        return $this->last_sync_at && $this->last_sync_at >= \Minz\Time::ago(24, 'hours');
    }

    /**
     * Return the list of payments associated to this account
     *
     * @return Payment[]
     */
    public function payments(): array
    {
        return Payment::listBy([
            'account_id' => $this->id,
        ]);
    }

    /**
     * Return an ongoing payment associated to this account, if any
     */
    public function ongoingPayment(): ?Payment
    {
        return Payment::findOngoingForAccount($this->id);
    }

    /**
     * Return the list of accounts with computed count_payments
     *
     * @return self[]
     */
    public static function listWithCountPayments(): array
    {
        $sql = <<<SQL
            SELECT a.*, (
                SELECT COUNT(p.id) FROM payments p
                WHERE p.account_id = a.id
            ) AS count_payments
            FROM accounts a
        SQL;

        $database = Database::get();
        $statement = $database->query($sql);
        return self::fromDatabaseRows($statement->fetchAll());
    }

    /**
     * Update the last_sync_at of the given accounts.
     *
     * @param string[] $account_ids
     */
    public static function updateLastSyncAt(array $account_ids, \DateTimeImmutable $date): bool
    {
        $question_marks = array_fill(0, count($account_ids), '?');
        $in_statement = implode(',', $question_marks);

        $sql = <<<SQL
            UPDATE accounts
            SET last_sync_at = ?
            WHERE id IN ({$in_statement})
        SQL;

        $database = \Minz\Database::get();
        $statement = $database->prepare($sql);
        $parameters = [
            $date->format(Database\Column::DATETIME_FORMAT),
        ];
        $parameters = array_merge($parameters, $account_ids);
        return $statement->execute($parameters);
    }

    /**
     * List the accounts which have a last_sync_at property older than the
     * given date.
     *
     * @return self[]
     */
    public static function listByLastSyncAtOlderThan(\DateTimeImmutable $date): array
    {
        $sql = <<<SQL
            SELECT * FROM accounts
            WHERE last_sync_at < ? OR last_sync_at IS NULL
        SQL;

        $database = Database::get();
        $statement = $database->prepare($sql);
        $statement->execute([
            $date->format(Database\Column::DATETIME_FORMAT),
        ]);
        return self::fromDatabaseRows($statement->fetchAll());
    }

    /**
     * Return the number of accounts with an active subscription.
     *
     * This counts only accounts which already made a payment, in order to
     * exclude accounts using the first free month.
     */
    public static function countActive(): int
    {
        $sql = <<<SQL
            SELECT COUNT(DISTINCT a.id) FROM accounts a
            INNER JOIN payments p
            ON p.account_id = a.id
            WHERE a.expired_at >= :now
        SQL;

        $now = \Minz\Time::now();
        $database = Database::get();
        $statement = $database->prepare($sql);
        $statement->execute([
            'now' => $now->format(Database\Column::DATETIME_FORMAT),
        ]);

        return intval($statement->fetchColumn());
    }
}
