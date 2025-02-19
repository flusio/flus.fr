<?php

namespace Website\mailers;

use Website\models;
use Minz\Mailer;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Support extends Mailer
{
    public function sendMessage(models\Message $message): Mailer\Email
    {
        $email = new Mailer\Email();
        $email->setSubject('[Flus] Contact : ' . $message->subject);
        $email->setBody(
            'mailers/support/message.phtml',
            'mailers/support/message.txt',
            [
                'content' => $message->content,
            ]
        );
        $email->addReplyTo($message->email);

        $to = \Minz\Configuration::$application['support_email'];
        $this->send($email, to: $to);

        return $email;
    }
}
