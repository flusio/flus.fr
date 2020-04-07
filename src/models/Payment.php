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

        'completed' => [
            'type' => 'boolean',
            'required' => true,
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
    ];

    /**
     * Initialize a Payment object from user request parameters.
     *
     * While a Payment object always manipulates amounts as cent values, the
     * `init` method takes the amount in euros. This is why float are accepted.
     *
     * @param string $email
     * @param integer|float $amount
     *
     * @throws \Minz\Errors\ModelPropertyError if a value is invalid
     *
     * @return \Website\models\Payment
     */
    public static function init($email, $amount)
    {
        return new self([
            'email' => strtolower($email),
            'amount' => intval($amount * 100),
            'completed' => false,
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
}
