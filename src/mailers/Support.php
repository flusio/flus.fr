<?php

namespace Website\mailers;

use Website\models;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Support extends \Minz\Mailer
{
    public function sendMessage(models\Message $message): bool
    {
        $subject = '[Flus] Contact : ' . $message->subject;
        $this->setBody(
            'mailers/support/message.phtml',
            'mailers/support/message.txt',
            [
                'content' => $message->content,
            ]
        );
        $this->mailer->addReplyTo($message->email);

        $to = \Minz\Configuration::$application['support_email'];
        return $this->send($to, $subject);
    }
}
