<?php

namespace Website\models;

/**
 * A Payment represents a payment by a customer. It allows easy manipulations
 * from Stripe service to the database.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Payment extends \Minz\Model
{
    public const MIN_AMOUNT = 1 * 100;
    public const MAX_AMOUNT = 1000 * 100;

    public const PROPERTIES = [
        'id' => 'integer',

        'created_at' => 'datetime',

        'completed_at' => 'datetime',

        'invoice_number' => [
            'type' => 'string',
            'validator' => '\Website\models\Payment::validateInvoiceNumber',
        ],

        'type' => [
            'type' => 'string',
            'required' => true,
            'validator' => '\Website\models\Payment::validateType',
        ],

        'email' => [
            'type' => 'string',
            'required' => true,
            'validator' => '\Website\models\Payment::validateEmail',
        ],

        'amount' => [
            'type' => 'integer',
            'required' => true,
            'validator' => '\Website\models\Payment::validateAmount',
        ],

        'payment_intent_id' => [
            'type' => 'string',
            'validator' => '\Website\models\Payment::validatePaymentIntentId',
        ],

        'address_first_name' => [
            'type' => 'string',
            'required' => true,
        ],

        'address_last_name' => [
            'type' => 'string',
            'required' => true,
        ],

        'address_address1' => [
            'type' => 'string',
            'required' => true,
        ],

        'address_postcode' => [
            'type' => 'string',
            'required' => true,
        ],

        'address_city' => [
            'type' => 'string',
            'required' => true,
        ],

        'username' => [
            'type' => 'string',
            'validator' => '\Website\models\Payment::validateUsername',
        ],

        'frequency' => [
            'type' => 'string',
            'validator' => '\Website\models\Payment::validateFrequency',
        ],
    ];

    /**
     * Initialize a Payment object from user request parameters.
     *
     * While a Payment object always manipulates amounts as cent values, the
     * `init` method takes the amount in euros. This is why float are accepted.
     *
     * @param string $type
     * @param string $email
     * @param integer|float $amount
     * @param array $address
     *
     * @throws \Minz\Errors\ModelPropertyError if a value is invalid
     *
     * @return \Website\models\Payment
     */
    public static function init($type, $email, $amount, $address)
    {
        if (!is_numeric($amount)) {
            throw new \Minz\Errors\ModelPropertyError(
                'amount',
                \Minz\Errors\ModelPropertyError::VALUE_INVALID,
                "`amount` property is invalid ({$amount})."
            );
        }

        return new self([
            'type' => $type,
            'email' => strtolower(trim($email)),
            'amount' => intval($amount * 100),
            'address_first_name' => trim($address['first_name']),
            'address_last_name' => trim($address['last_name']),
            'address_address1' => trim($address['address1']),
            'address_postcode' => trim($address['postcode']),
            'address_city' => trim($address['city']),
        ]);
    }

    /**
     * @param array $values
     *
     * @throws \Minz\Errors\ModelPropertyError if a value is invalid
     */
    public function __construct($values)
    {
        parent::__construct(self::PROPERTIES);
        $this->fromValues($values);
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
        ];
    }

    /**
     * Mark the payment as completed
     */
    public function complete()
    {
        $now = \Minz\Time::now();

        $payment_dao = new dao\Payment();
        $last_invoice_number = $payment_dao->findLastInvoiceNumber();
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

        $invoice_number = $now->format('Y-m') . sprintf('-%04d', $invoice_sequence);
        $this->setProperty('completed_at', $now);
        $this->setProperty('invoice_number', $invoice_number);
    }

    /**
     * @param string $invoice_number
     *
     * @return boolean Returns true if the number is valid
     */
    public static function validateInvoiceNumber($invoice_number)
    {
        $pattern = '/^[1-9][0-9]{3}-[0-9]{2}-[0-9]{4}$/';
        return preg_match($pattern, $invoice_number) === 1;
    }

    /**
     * @param string $type
     *
     * @return boolean Returns true if the value is either `common_pot` or
     *                 `subscription`
     */
    public static function validateType($type)
    {
        return $type === 'common_pot' || $type === 'subscription';
    }

    /**
     * @param string $email
     *
     * @return boolean Returns true if the value is a valid email, false otherwise
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @param integer $amount
     *
     * @return boolean Returns true if the value is between MIN_AMOUNT and MAX_AMOUNT
     */
    public static function validateAmount($amount)
    {
        return $amount >= self::MIN_AMOUNT && $amount <= self::MAX_AMOUNT;
    }

    /**
     * @param string $id
     *
     * @return boolean Returns true if the value is not empty
     */
    public static function validatePaymentIntentId($id)
    {
        return strlen($id) > 0;
    }

    /**
     * @param string $username
     *
     * @return boolean Returns true if the username is valid
     */
    public static function validateUsername($username)
    {
        // This is the same pattern as in FreshRSS
        // @see https://github.com/FreshRSS/FreshRSS/blob/master/app/Controllers/userController.php#L11
        $pattern = '/^([0-9a-zA-Z_][0-9a-zA-Z_.@-]{1,38}|[0-9a-zA-Z])$/';
        return preg_match($pattern, $username) === 1;
    }

    /**
     * @param string $frequency
     *
     * @return boolean Returns true if the value is either `month` or `year`
     */
    public static function validateFrequency($frequency)
    {
        return $frequency === 'month' || $frequency === 'year';
    }
}
