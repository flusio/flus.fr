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
 * Format a date with strftime.
 *
 * @param \DateTime $date
 * @param string $format
 *
 * @return string
 */
function format_date($date, $format)
{
    return strftime($format, $date->getTimestamp());
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
    $date = date_create_from_format('n', $month);
    return strftime($format, $date->getTimestamp());
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
