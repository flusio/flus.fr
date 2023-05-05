<?php

namespace Website\jobs;

use Minz\Job;
use Website\models;
use Website\services;

class PaymentsCompleter extends Job
{
    public static function install(): void
    {
        $job = new self();
        if (!self::existsBy(['name' => $job->name])) {
            $perform_at = \Minz\Time::now();
            $job->performLater($perform_at);
        }
    }

    public function __construct()
    {
        parent::__construct();
        $this->frequency = '+5 seconds';
    }

    public function perform(): void
    {
        $payments = models\Payment::listBy([
            'completed_at' => null,
            'is_paid' => true,
        ]);
        $number_payments = count($payments);

        if ($number_payments === 0) {
            return;
        }

        \Minz\Log::notice("{$number_payments} to complete.");

        $payment_completer = new services\PaymentCompleter();

        foreach ($payments as $payment) {
            $payment_completer->complete($payment);
        }

        \Minz\Log::notice('Payments completed.');
    }
}
