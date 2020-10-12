<?php

namespace Website\mailers;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Accounts extends \Minz\Mailer
{
    /**
     * @param \Website\models\Account $account
     *
     * @return boolean
     */
    public function sendReminderSubscriptionEnding($account)
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
            ]
        );

        return $this->send($account->email, $subject);
    }

    /**
     * @param \Website\models\Account $account
     *
     * @return boolean
     */
    public function sendReminderSubscriptionEnded($account)
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
            ]
        );
        return $this->send($account->email, $subject);
    }
}
