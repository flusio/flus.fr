<?php

namespace Website\mailers;

use Website\models;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Accounts extends \Minz\Mailer
{
    public function sendReminderSubscriptionEnding(models\Account $account): bool
    {
        $subject = '[Flus] Votre abonnement arrive à échéance';
        $this->setBody(
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

        return $this->send($account->email, $subject);
    }

    public function sendReminderSubscriptionEnded(models\Account $account): bool
    {
        $subject = '[Flus] Votre abonnement a expiré';
        $this->setBody(
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
        return $this->send($account->email, $subject);
    }
}
