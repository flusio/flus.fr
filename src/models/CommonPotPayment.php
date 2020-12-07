<?php

namespace Website\models;

use Website\utils;

/**
 * A CommonPotPayment represents a payment by a customer made via the common
 * pot. It’s different from a Payment because it has no invoice attached and
 * doesn’t involve a payment via Stripe.
 *
 * As opposed to Payment, a CommonPotPayment is always completed because it
 * doesn't involve the Stripe service. If the common pot is full enough, the
 * CommonPotPayment is created. If it’s not, the payment is not created and is
 * refused to the user.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class CommonPotPayment extends \Minz\Model
{
    public const PROPERTIES = [
        'id' => [
            'type' => 'string',
            'required' => true,
        ],

        'created_at' => 'datetime',

        'completed_at' => [
            'type' => 'datetime',
            'required' => true,
        ],

        'is_paid' => [
            'type' => 'boolean',
            'required' => true,
        ],

        'amount' => [
            'type' => 'integer',
            'required' => true,
            'validator' => '\Website\models\Payment::validateAmount',
        ],

        'frequency' => [
            'type' => 'string',
            'required' => true,
            'validator' => '\Website\models\Payment::validateFrequency',
        ],

        'account_id' => [
            'type' => 'string',
        ],
    ];

    /**
     * Init a subscription payment from an account.
     *
     * @param \Website\models\Account $account
     * @param string $frequency (`month` or `year`)
     *
     * @return \Website\models\CommonPotPayment
     */
    public static function initFromAccount($account, $frequency)
    {
        $frequency = strtolower(trim($frequency));
        $amount = 0;
        if ($frequency === 'month') {
            $amount = 3;
        } elseif ($frequency === 'year') {
            $amount = 30;
        }

        return new self([
            'id' => bin2hex(random_bytes(16)),
            'completed_at' => \Minz\Time::now(),
            'is_paid' => true,
            'amount' => $amount * 100,
            'frequency' => $frequency,
            'account_id' => $account->id,
        ]);
    }

    /**
     * Return the account associated to the payment if any. It might be null if
     * the account has been deleted.
     *
     * @return \Website\models\Account|null
     */
    public function account()
    {
        if (!$this->account_id) {
            return null;
        }

        $account_dao = new dao\Account();
        $db_account = $account_dao->find($this->account_id);
        if (!$db_account) {
            return null;
        }

        return new Account($db_account);
    }

    /**
     * Validate a model and return formated errors
     *
     * @return string[]
     */
    public function validate()
    {
        $formatted_errors = [];

        foreach (parent::validate() as $property => $error) {
            $code = $error['code'];

            if ($property === 'frequency') {
                $formatted_error = 'Vous devez choisir l’une des deux périodes proposées.';
            } else {
                $formatted_error = $error['description']; // @codeCoverageIgnore
            }

            $formatted_errors[$property] = $formatted_error;
        }

        return $formatted_errors;
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
     * @param string $frequency
     *
     * @return boolean Returns true if the value is either `month` or `year`
     */
    public static function validateFrequency($frequency)
    {
        return $frequency === 'month' || $frequency === 'year';
    }
}
