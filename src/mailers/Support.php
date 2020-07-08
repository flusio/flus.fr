<?php

namespace Website\mailers;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Support extends \Minz\Mailer
{
    /**
     * @param \Website\models\Message $message
     *
     * @return boolean
     */
    public function sendMessage($message)
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

    /**
     * @param \Website\models\Message $message
     *
     * @return boolean
     */
    public function sendNotification($message)
    {
        $subject = '[Flus] Votre message a bien été envoyé';
        $this->setBody(
            'mailers/support/notification.phtml',
            'mailers/support/notification.txt',
            [
                'subject' => $message->subject,
            ]
        );

        return $this->send($message->email, $subject);
    }
}
