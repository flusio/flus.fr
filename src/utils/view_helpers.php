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

/**
 * Return a SVG icon.
 */
function icon(string $icon_name, string $additional_class_names = ''): string
{
    $class = "icon icon--{$icon_name}";
    if ($additional_class_names) {
        $class .= ' ' . $additional_class_names;
    }

    $url_icons = \Minz\Output\ViewHelpers::urlStatic('icons/icons.svg');
    $svg = "<svg class=\"{$class}\" aria-hidden=\"true\" width=\"36\" height=\"36\">";
    $svg .= "<use xlink:href=\"{$url_icons}#{$icon_name}\"/>";
    $svg .= '</svg>';
    return $svg;
}
