<?php

namespace Website\models;

/**
 * A Message sent by email from the contact form.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Message extends \Minz\Model
{
    public const PROPERTIES = [
        'email' => [
            'type' => 'string',
            'required' => true,
            'validator' => '\Website\models\Message::validateEmail',
        ],

        'subject' => [
            'type' => 'string',
            'required' => true,
        ],

        'content' => [
            'type' => 'string',
            'required' => true,
        ],
    ];

    /**
     * @param string $email
     * @param string $subject
     * @param string $content
     *
     * @return \Website\models\Message
     */
    public static function init($email, $subject, $content)
    {
        return new self([
            'email' => strtolower(trim($email)),
            'subject' => trim($subject),
            'content' => trim($content),
        ]);
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

            if ($property === 'email') {
                if ($code === \Minz\Model::ERROR_REQUIRED) {
                    $formatted_error = 'L’adresse courriel est obligatoire.';
                } else {
                    $formatted_error = 'L’adresse courriel que vous avez fournie est invalide.';
                }
            } elseif ($property === 'subject') {
                $formatted_error = 'Le sujet est obligatoire';
            } elseif ($property === 'content') {
                $formatted_error = 'Le message est obligatoire.';
            } else {
                $formatted_error = $error; // @codeCoverageIgnore
            }

            $formatted_errors[$property] = $formatted_error;
        }

        return $formatted_errors;
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
}
