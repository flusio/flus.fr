<?php

namespace Website\models;

use Website\utils;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Account extends \Minz\Model
{
    public const PROPERTIES = [
        'id' => [
            'type' => 'string',
            'required' => true,
        ],

        'created_at' => [
            'type' => 'datetime',
        ],

        'expired_at' => [
            'type' => 'datetime',
            'required' => true,
        ],

        'email' => [
            'type' => 'string',
            'required' => true,
            'validator' => '\Website\utils\Email::validate',
        ],

        'access_token' => [
            'type' => 'string',
        ],

        'preferred_frequency' => [
            'type' => 'string',
            'validator' => '\Website\models\Account::validateFrequency',
        ],

        'preferred_payment_type' => [
            'type' => 'string',
            'validator' => '\Website\models\Account::validatePaymentType',
        ],

        'reminder' => [
            'type' => 'boolean',
            'required' => true,
        ],

        'address_first_name' => [
            'type' => 'string',
        ],

        'address_last_name' => [
            'type' => 'string',
        ],

        'address_address1' => [
            'type' => 'string',
        ],

        'address_postcode' => [
            'type' => 'string',
        ],

        'address_city' => [
            'type' => 'string',
        ],

        'address_country' => [
            'type' => 'string',
            'validator' => '\Website\utils\Countries::isSupported',
        ],
    ];

    /**
     * Initialize an Account
     *
     * @param string $email
     *
     * @return \Website\models\Account
     */
    public static function init($email)
    {
        return new self([
            'id' => bin2hex(random_bytes(16)),
            'email' => utils\Email::sanitize($email),
            'expired_at' => \Minz\Time::fromNow(1, 'month'),
            'reminder' => false,
        ]);
    }

    /**
     * @param string $expired_at
     */
    public function setExpiredAt($expired_at)
    {
        $this->expired_at = date_create_from_format(\Minz\Model::DATETIME_FORMAT, $expired_at);
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
     * Validate a model and return formated errors
     *
     * @return string[]
     */
    public function validate()
    {
        $formatted_errors = [];

        foreach (parent::validate() as $property => $error) {
            $code = $error['code'];

            if ($property === 'email' && $code === \Minz\Model::ERROR_VALUE_INVALID) {
                $formatted_error = 'L’adresse courriel que vous avez fournie est invalide.';
            } else {
                $formatted_error = $error['description']; // @codeCoverageIgnore
            }

            $formatted_errors[$property] = $formatted_error;
        }

        return $formatted_errors;
    }

    /**
     * @param string $type
     *
     * @return boolean Returns true if the value is either `common_pot` or `card`
     */
    public static function validatePaymentType($type)
    {
        return $type === 'common_pot' || $type === 'card';
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
