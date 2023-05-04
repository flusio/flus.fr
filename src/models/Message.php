<?php

namespace Website\models;

use Minz\Validable;

/**
 * A Message sent by email from the contact form.
 *
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Message
{
    use Validable;

    #[Validable\Presence(message: 'Saisissez une adresse courriel.')]
    #[Validable\Email(message: 'Saisissez une adresse courriel valide.')]
    public string $email;

    #[Validable\Presence(message: 'Saisissez un sujet.')]
    public string $subject;

    #[Validable\Presence(message: 'Saisissez un message.')]
    public string $content;

    public function __construct(string $email, string $subject, string $content)
    {
        $this->email = \Minz\Email::sanitize($email);
        $this->subject = trim($subject);
        $this->content = trim($content);
    }
}
