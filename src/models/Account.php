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
 *     'legal_name': string,
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

    #[Validable\Inclusion(in: ['flus', 'freshrss'], message: 'Saisissez un service valide.')]
    #[Database\Column]
    public string $preferred_service;

    #[Database\Column]
    public string $preferred_tariff;

    #[Database\Column]
    public bool $reminder;

    #[Validable\Inclusion(in: ['natural', 'legal'], message: 'Saisissez un choix valide.')]
    #[Database\Column]
    public string $entity_type;

    public bool $show_address = false;

    #[Database\Column]
    public ?string $address_first_name;

    #[Database\Column]
    public ?string $address_last_name;

    #[Database\Column]
    public ?string $address_legal_name;

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

    #[Validable\Length(min: 10, max: 20, message: 'Saisissez un numéro de TVA valide.')]
    #[Database\Column]
    public ?string $company_vat_number = null;

    #[Database\Column]
    public ?string $managed_by_id = null;

    #[Database\Column(computed: true)]
    public int $count_payments;

    public function __construct(string $email)
    {
        $this->id = \Minz\Random::hex(32);
        $this->email = \Minz\Email::sanitize($email);
        $this->expired_at = \Minz\Time::fromNow(31, 'days');
        $this->preferred_service = 'flus';
        $this->preferred_tariff = 'stability';
        $this->reminder = true;
        $this->entity_type = 'natural';
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

    /**
     * Return the preferred amount based on preferred tariff.
     */
    public function preferredAmount(): int
    {
        if ($this->preferred_tariff === 'solidarity') {
            return 15;
        } elseif ($this->preferred_tariff === 'stability') {
            return 30;
        } elseif ($this->preferred_tariff === 'contribution') {
            return Payment::contributionPrice();
        } else {
            return intval($this->preferred_tariff);
        }
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
    public function address(): array
    {
        return [
            'first_name' => $this->address_first_name ?? '',
            'last_name' => $this->address_last_name ?? '',
            'legal_name' => $this->address_legal_name ?? '',
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
     *     'legal_name'?: string,
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
        $this->address_legal_name = trim($address['legal_name'] ?? '');
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
        return !$this->address_first_name && !$this->address_legal_name;
    }

    #[Validable\Check]
    public function checkAddress(): void
    {
        $address = $this->address();
        if ($this->entity_type === 'natural') {
            if (!$address['first_name']) {
                $this->addError(
                    'address_first_name',
                    'missing_first_name',
                    'Votre prénom est obligatoire.'
                );
            }

            if (!$address['last_name']) {
                $this->addError(
                    'address_last_name',
                    'missing_last_name',
                    'Votre nom est obligatoire.'
                );
            }
        } elseif (!$address['legal_name']) {
            $this->addError(
                'address_legal_name',
                'missing_legal_name',
                'Votre raison sociale est obligatoire.'
            );
        }

        if ($this->show_address) {
            if (!$address['address1']) {
                $this->addError(
                    'address_address1',
                    'invalid_address',
                    'Votre adresse est incomplète.'
                );
            }

            if (!$address['postcode']) {
                $this->addError(
                    'address_postcode',
                    'invalid_address',
                    'Votre adresse est incomplète.'
                );
            }

            if (!$address['city']) {
                $this->addError(
                    'address_city',
                    'invalid_address',
                    'Votre adresse est incomplète.'
                );
            }
        }
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
     * account on the connected services (i.e. Flus and/or FreshRSS).
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
     * Return the list of the accounts managed by the current one.
     *
     * @return Account[]
     */
    public function managedAccounts(): array
    {
        $accounts = self::listBy([
            'managed_by_id' => $this->id,
        ]);

        usort($accounts, function ($account1, $account2): int {
            return $account1->email <=> $account2->email;
        });

        return $accounts;
    }

    /**
     * Return the number of the accounts managed by the current one.
     */
    public function countManagedAccounts(): int
    {
        return self::countBy([
            'managed_by_id' => $this->id,
        ]);
    }

    /**
     * Return whether the current account is managed by another one.
     */
    public function isManaged(): bool
    {
        return $this->managed_by_id !== null;
    }

    /**
     * Return the list of accounts with computed count_payments
     *
     * @return self[]
     */
    public static function listWithCountPayments(): array
    {
        $email = \Minz\Configuration::$application['support_email'];

        $sql = <<<SQL
            SELECT a.*, (
                SELECT COUNT(p.id) FROM payments p
                WHERE p.account_id = a.id
            ) AS count_payments
            FROM accounts a
            WHERE a.email != :email
        SQL;

        $database = Database::get();
        $statement = $database->prepare($sql);
        $statement->execute([
            ':email' => $email,
        ]);
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
     * given date and that are not managed by another account.
     *
     * @return self[]
     */
    public static function listToBeDeleted(\DateTimeImmutable $date): array
    {
        $sql = <<<SQL
            SELECT * FROM accounts
            WHERE (last_sync_at < ? OR last_sync_at IS NULL)
            AND managed_by_id IS NULL
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
