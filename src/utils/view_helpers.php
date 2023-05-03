<?php

/**
 * Format an amount to euros
 *
 * @param integer $amount
 *
 * @return string
 */
function format_amount($amount)
{
    return number_format($amount / 100, 2, ',', '&nbsp;') . '&nbsp;€';
}

/**
 * Format a month number with strftime.
 *
 * @param integer $month
 * @param string $format
 *
 * @return string
 */
function format_month($month, $format)
{
    $date = date_create_from_format('n', strval($month));
    return \Minz\Output\ViewHelpers::formatDate($date, $format);
}

/**
 * Format a message into hmtl
 *
 * @param string $message
 *
 * @return string
 */
function format_message($message)
{
    return nl2br($message);
}
