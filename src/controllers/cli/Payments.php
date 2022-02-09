<?php

namespace Website\controllers\cli;

use Website\models;
use Website\services;

/**
 * @author Marien Fressinaud <dev@marienfressinaud.fr>
 * @license http://www.gnu.org/licenses/agpl-3.0.en.html AGPL
 */
class Payments
{
    /**
     * @response 200
     */
    public function complete($request)
    {
        $payments = models\Payment::listBy([
            'completed_at' => null,
            'is_paid' => 1,
        ]);
        $number_payments = count($payments);
        $payment_completer = new services\PaymentCompleter();

        foreach ($payments as $payment) {
            $payment_completer->complete($payment);
        }

        if ($number_payments > 0) {
            return \Minz\Response::text(200, "{$number_payments} payments completed");
        } else {
            return \Minz\Response::text(200, '');
        }
    }
}
