<?php

namespace Website\models;

use Website\utils;

/**
 * A Payment represents a payment by a customer. It allows easy manipulations
 * from Stripe service to the database.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Payment extends \Minz\Model
{
    use DaoConnector;

    public const MIN_AMOUNT = 1 * 100;
    public const MAX_AMOUNT = 1000 * 100;

    public const PROPERTIES = [
        'id' => [
            'type' => 'string',
            'required' => true,
        ],

        'created_at' => 'datetime',

        'completed_at' => 'datetime',

        'is_paid' => [
            'type' => 'boolean',
            'required' => true,
        ],

        'invoice_number' => [
            'type' => 'string',
            'validator' => '\Website\models\Payment::validateInvoiceNumber',
        ],

        'type' => [
            'type' => 'string',
            'required' => true,
            'validator' => '\Website\models\Payment::validateType',
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

        'session_id' => [
            'type' => 'string',
            'validator' => '\Website\models\Payment::validateSessionId',
        ],

        'frequency' => [
            'type' => 'string',
            'validator' => '\Website\models\Payment::validateFrequency',
        ],

        'credited_payment_id' => [
            'type' => 'string',
        ],

        'account_id' => [
            'required' => true,
            'type' => 'string',
        ],
    ];

    /**
     * Initialize a Payment object from user request parameters.
     *
     * While a Payment object always manipulates amounts as cent values, the
     * `init` method takes the amount in euros. This is why float are accepted.
     *
     * @param string $type
     * @param integer|float $amount
     *
     * @return \Website\models\Payment
     */
    private static function init($type, $amount)
    {
        return new self([
            'id' => bin2hex(random_bytes(16)),
            'type' => $type,
            'amount' => is_numeric($amount) ? intval($amount * 100) : $amount,
            'is_paid' => false,
        ]);
    }

    /**
     * Init a subscription payment from an account.
     *
     * @param \Website\models\Account $account
     * @param string $frequency (`month` or `year`)
     *
     * @return \Website\models\Payment
     */
    public static function initSubscriptionFromAccount($account, $frequency)
    {
        $frequency = strtolower(trim($frequency));
        $amount = 0;
        if ($frequency === 'month') {
            $amount = 3;
        } elseif ($frequency === 'year') {
            $amount = 30;
        }

        $payment = self::init('subscription', $amount);
        $payment->frequency = $frequency;
        $payment->account_id = $account->id;

        return $payment;
    }

    /**
     * Init a common pot payment from an account.
     *
     * @param \Website\models\Account $account
     * @param integer|float $amount
     *
     * @return \Website\models\Payment
     */
    public static function initCommonPotFromAccount($account, $amount)
    {
        $payment = self::init('common_pot', $amount);
        $payment->account_id = $account->id;

        return $payment;
    }

    /**
     * Init a credit payment from a payment.
     *
     * @param \Website\models\Payment $payment
     *
     * @return \Website\models\Payment
     */
    public static function initCreditFromPayment($payment)
    {
        return new self([
            'id' => bin2hex(random_bytes(16)),
            'type' => 'credit',
            'amount' => $payment->amount,
            'account_id' => $payment->account_id,
            'credited_payment_id' => $payment->id,
            'is_paid' => false,
        ]);
    }

    /**
     * Return the account associated to the payment if any
     *
     * @return \Website\models\Account|null
     */
    public function account()
    {
        if (!$this->account_id) {
            return null;
        }

        return Account::find($this->account_id);
    }

    /**
     * @return string|null
     */
    public function invoiceFilepath()
    {
        if (!$this->invoice_number) {
            return null;
        }

        $invoices_path = \Minz\Configuration::$data_path . '/invoices';
        return $invoices_path . '/' . $this->invoiceFilename();
    }

    /**
     * @return string|null
     */
    public function invoiceFilename()
    {
        if (!$this->invoice_number) {
            return null;
        }

        return "facture_{$this->invoice_number}.pdf";
    }

    /**
     * @return boolean
     */
    public function invoiceExists()
    {
        if (!$this->invoice_number) {
            return false;
        }

        return file_exists($this->invoiceFilepath());
    }

    /**
     * @return integer
     */
    public function stripeFees()
    {
        if ($this->payment_intent_id) {
            return intval(floor($this->amount * 0.014) + 25);
        } else {
            return 0;
        }
    }

    /**
     * @return boolean
     */
    public function isReimbursed()
    {
        $credited_payment = Payment::findBy([
            'credited_payment_id' => $this->id,
        ]);
        return $credited_payment !== null;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        $attributes = [
            'id' => $this->id,
            'created_at' => $this->created_at->getTimestamp(),
            'completed_at' => null,
            'frequency' => $this->frequency,
            'amount' => $this->amount,
        ];
        if ($this->completed_at) {
            $attributes['completed_at'] = $this->completed_at->getTimestamp();
        }
        return json_encode($attributes);
    }

    /**
     * Mark the payment as completed
     *
     * @param \DateTime $completed_at
     */
    public function complete($completed_at)
    {
        if ($this->is_paid) {
            $this->completed_at = $completed_at;
            if (!$this->invoice_number) {
                $this->invoice_number = self::generateInvoiceNumber();
            }
        }
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

            if ($property === 'amount') {
                $formatted_error = 'Le montant doit être compris entre 1 et 1000 €.';
            } elseif ($property === 'frequency') {
                $formatted_error = 'Vous devez choisir l’une des deux périodes proposées.';
            } else {
                $formatted_error = $error['description']; // @codeCoverageIgnore
            }

            $formatted_errors[$property] = $formatted_error;
        }

        return $formatted_errors;
    }

    /**
     * @return string
     */
    public static function generateInvoiceNumber()
    {
        $now = \Minz\Time::now();

        $last_invoice_number = Payment::daoCall('findLastInvoiceNumber');
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
     *                 `subscription` or `credit`
     */
    public static function validateType($type)
    {
        return $type === 'common_pot' || $type === 'subscription' || $type === 'credit';
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
     * @param string $id
     *
     * @return boolean Returns true if the value is not empty
     */
    public static function validateSessionId($id)
    {
        return strlen($id) > 0;
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
