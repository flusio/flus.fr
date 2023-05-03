<?php

namespace Website\models;

use Minz\Database;
use Minz\Validable;
use Website\utils;

/**
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

    public function __construct($email)
    {
        $this->id = \Minz\Random::hex(32);
        $this->email = \Minz\Email::sanitize($email);
        $this->expired_at = \Minz\Time::fromNow(1, 'month');
        $this->preferred_frequency = 'month';
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
     *
     * @return \Website\models\Account
     */
    public static function defaultAccount()
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
     * Extend the subscription period by the given frequency
     *
     * @param string $frequency (`month` or `year`)
     */
    public function extendSubscription($frequency)
    {
        if ($this->isFree()) {
            // Free accounts don't need to be extended
            return;
        }

        $today = \Minz\Time::now();
        $latest_date = max($today, $this->expired_at);
        if ($frequency === 'year') {
            $this->expired_at = $latest_date->modify('+1 year');
        } else {
            $this->expired_at = $latest_date->modify('+1 month');
        }
    }

    /**
     * @param string $access_token
     *
     * @return boolean True if the given token is valid, false else
     */
    public function checkAccess($access_token)
    {
        if (!$this->access_token || !$access_token) {
            return false;
        }

        $equals = hash_equals($this->access_token, $access_token);
        if (!$equals) {
            return false;
        }

        $token = Token::find($this->access_token);
        return $token->isValid();
    }

    /**
     * Return the address information as an array
     *
     * @return array
     */
    public function address()
    {
        return [
            'first_name' => $this->address_first_name,
            'last_name' => $this->address_last_name,
            'address1' => $this->address_address1,
            'postcode' => $this->address_postcode,
            'city' => $this->address_city,
            'country' => $this->address_country,
        ];
    }

    /**
     * @param array $address
     */
    public function setAddress($address)
    {
        $this->address_first_name = trim($address['first_name'] ?? '');
        $this->address_last_name = trim($address['last_name'] ?? '');
        $this->address_address1 = trim($address['address1'] ?? '');
        $this->address_postcode = trim($address['postcode'] ?? '');
        $this->address_city = trim($address['city'] ?? '');
        $this->address_country = trim($address['country'] ?? '');
    }

    /**
     * Return whether the user needs to set its address or not.
     *
     * @return boolean
     */
    public function mustSetAddress()
    {
        return !$this->address_first_name;
    }

    /**
     * Return whether the account has a free subscription or not
     *
     * @return boolean
     */
    public function isFree()
    {
        return $this->expired_at->getTimestamp() === 0;
    }

    /**
     * Return whether the subscription has expired or not
     *
     * @return boolean
     */
    public function hasExpired()
    {
        return !$this->isFree() && $this->expired_at <= \Minz\Time::now();
    }

    /**
     * Return whether the account is sync or not.
     *
     * If the account is not sync, it probably means the user deleted its
     * account on the connected services (i.e. flusio and/or FreshRSS).
     *
     * @return boolean
     */
    public function isSync()
    {
        return $this->last_sync_at && $this->last_sync_at >= \Minz\Time::ago(24, 'hours');
    }

    /**
     * Return the list of payments associated to this account
     *
     * @return \Website\models\Payment[]
     */
    public function payments()
    {
        return Payment::listBy([
            'account_id' => $this->id,
        ]);
    }

    /**
     * Return an ongoing payment associated to this account, if any
     *
     * @return ?\Website\models\Payment
     */
    public function ongoingPayment()
    {
        return Payment::findOngoingForAccount($this->id);
    }

    /**
     * Return the list of accounts with computed count_payments
     *
     * @return array
     */
    public static function listWithCountPayments()
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
     * @param \DateTimeImmutable $date
     *
     * @return boolean True on success or false on failure
     */
    public static function updateLastSyncAt($account_ids, $date)
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
     * @param \DateTimeImmutable $date
     *
     * @return array
     */
    public static function listByLastSyncAtOlderThan($date)
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
}
