<?php

namespace Website\mailers;

use Website\models;
use Minz\Mailer;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Accounts extends Mailer
{
    public function sendReminderSubscriptionEnding(models\Account $account): Mailer\Email
    {
        $email = new Mailer\Email();
        $email->setSubject('[Flus] Votre abonnement arrive à échéance');
        $email->setBody(
            'mailers/accounts/reminder_subscription_ending.phtml',
            'mailers/accounts/reminder_subscription_ending.txt',
            [
                'expired_at' => $account->expired_at,
                'login_url' => \Minz\Url::absoluteFor('account login', [
                    'account_id' => $account->id,
                    'access_token' => $account->access_token,
                ]),
                'service' => $account->preferred_service,
            ]
        );

        $this->send($email, to: $account->email);

        return $email;
    }

    public function sendReminderSubscriptionEnded(models\Account $account): Mailer\Email
    {
        $email = new Mailer\Email();
        $email->setSubject('[Flus] Votre abonnement a expiré');
        $email->setBody(
            'mailers/accounts/reminder_subscription_ended.phtml',
            'mailers/accounts/reminder_subscription_ended.txt',
            [
                'expired_at' => $account->expired_at,
                'login_url' => \Minz\Url::absoluteFor('account login', [
                    'account_id' => $account->id,
                    'access_token' => $account->access_token,
                ]),
                'service' => $account->preferred_service,
            ]
        );

        $this->send($email, to: $account->email);

        return $email;
    }
}
