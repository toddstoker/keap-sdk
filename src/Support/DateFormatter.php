<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support;

use DateTimeInterface;

/**
 * Utility class for formatting dates to Keap API expected formats
 *
 * Keap's API expects dates in specific formats depending on the endpoint.
 * This class provides constants for common formats and a helper method
 * to convert DateTimeInterface objects to strings.
 */
final class DateFormatter
{
    public const string DATETIME = DateTimeInterface::RFC3339;

    public const string DATE = 'Y-m-d';

    /**
     * Format a date value to a string
     *
     * Accepts DateTimeInterface objects or strings. Strings are returned as-is,
     * allowing pre-formatted dates to pass through unchanged.
     *
     * @param  DateTimeInterface|string|null  $date  The date value to format
     * @param  string  $format  PHP date format string (defaults to DATETIME)
     * @return string|null The formatted date string, or null if input is null
     */
    public static function format(DateTimeInterface|string|null $date, string $format = self::DATETIME): ?string
    {
        if ($date === null) {
            return null;
        }

        if (is_string($date)) {
            return $date;
        }

        return $date->format($format);
    }

}
