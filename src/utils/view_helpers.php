<?php

/**
 * Format an amount to euros
 */
function format_amount(int $amount): string
{
    return number_format($amount / 100, 2, ',', '&nbsp;') . '&nbsp;€';
}

/**
 * Format a month number.
 */
function format_month(int $month, string $format): string
{
    $date = \DateTimeImmutable::createFromFormat('n', strval($month));
    if ($date === false) {
        return strval($month);
    } else {
        return \Minz\Output\ViewHelpers::formatDate($date, $format);
    }
}

/**
 * Format a message into hmtl.
 */
function format_message(string $message): string
{
    return nl2br($message);
}
