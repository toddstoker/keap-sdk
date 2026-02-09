<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support;

use DateTimeZone;

final class TimezoneNormalizer
{
    public const SUPPORTED_TIMEZONES = [
        'Pacific/Pago_Pago',
        'Pacific/Honolulu',
        'America/Adak',
        'Pacific/Marquesas',
        'America/Anchorage',
        'Pacific/Gambier',
        'America/Los_Angeles',
        'America/Santa_Isabel',
        'Pacific/Pitcairn',
        'America/Denver',
        'America/Mazatlan',
        'America/Phoenix',
        'America/Mexico_City',
        'America/Chicago',
        'America/Guatemala',
        'Pacific/Easter',
        'America/Bogota',
        'America/Havana',
        'America/New_York',
        'America/Caracas',
        'America/Campo_Grande',
        'America/Halifax',
        'America/Goose_Bay',
        'America/Santo_Domingo',
        'America/Santiago',
        'Atlantic/Stanley',
        'America/Asuncion',
        'America/St_Johns',
        'America/Argentina/Buenos_Aires',
        'America/Sao_Paulo',
        'America/Miquelon',
        'America/Montevideo',
        'America/Godthab',
        'America/Noronha',
        'Atlantic/Azores',
        'Atlantic/Cape_Verde',
        'Europe/London',
        'UTC',
        'Europe/Berlin',
        'Africa/Windhoek',
        'Africa/Lagos',
        'Asia/Damascus',
        'Europe/Istanbul',
        'Asia/Beirut',
        'Asia/Gaza',
        'Africa/Cairo',
        'Asia/Jerusalem',
        'Africa/Johannesburg',
        'Asia/Baghdad',
        'Europe/Minsk',
        'Asia/Tehran',
        'Asia/Yerevan',
        'Asia/Baku',
        'Asia/Dubai',
        'Europe/Moscow',
        'Asia/Kabul',
        'Asia/Karachi',
        'Asia/Kolkata',
        'Asia/Kathmandu',
        'Asia/Dhaka',
        'Asia/Yekaterinburg',
        'Asia/Rangoon',
        'Asia/Omsk',
        'Asia/Jakarta',
        'Asia/Shanghai',
        'Asia/Krasnoyarsk',
        'Australia/Eucla',
        'Asia/Irkutsk',
        'Asia/Tokyo',
        'Australia/Darwin',
        'Australia/Adelaide',
        'Australia/Brisbane',
        'Australia/Sydney',
        'Asia/Yakutsk',
        'Australia/Lord_Howe',
        'Pacific/Noumea',
        'Asia/Vladivostok',
        'Pacific/Norfolk',
        'Pacific/Fiji',
        'Pacific/Tarawa',
        'Pacific/Majuro',
        'Pacific/Auckland',
        'Asia/Kamchatka',
        'Pacific/Chatham',
        'Pacific/Tongatapu',
        'Pacific/Apia',
        'Pacific/Kiritimati',
    ];

    /** @var array<string, int>|null */
    protected static ?array $supportedMap = null;

    /** @return array<string, int> */
    protected static function supportedMap(): array
    {
        if (self::$supportedMap === null) {
            self::$supportedMap = array_flip(self::SUPPORTED_TIMEZONES);
        }

        return self::$supportedMap;
    }

    public static function normalize(string $timezone): ?string
    {
        $supported = self::supportedMap();

        if (isset($supported[$timezone])) {
            return $timezone;
        }

        // Normalize legacy timezone IDs (e.g. Asia/Calcutta => Asia/Kolkata)
        try {
            $canonical = (new DateTimeZone($timezone))->getName();
        } catch (\Exception) {
            return null;
        }

        if ($canonical !== $timezone && isset($supported[$canonical])) {
            return $canonical;
        }

        return self::matchByTransitions($canonical);
    }

    protected static function matchByTransitions(string $timezone): ?string
    {
        try {
            $tz = new DateTimeZone($timezone);
        } catch (\Exception) {
            return null;
        }

        $transitions = $tz->getTransitions(time(), time() + 86400 * 365);

        // Try exact transition match first
        foreach (self::SUPPORTED_TIMEZONES as $candidate) {
            $candidateTz = new DateTimeZone($candidate);

            if ($candidateTz->getTransitions(time(), time() + 86400 * 365) == $transitions) {
                return $candidate;
            }
        }

        // Fall back to closest UTC offset match
        return self::closestByOffset($tz);
    }

    protected static function closestByOffset(DateTimeZone $tz): ?string
    {
        $now = new \DateTime('now', $tz);
        $offset = $tz->getOffset($now);

        $bestMatch = null;
        $bestDiff = PHP_INT_MAX;

        foreach (self::SUPPORTED_TIMEZONES as $candidate) {
            $candidateTz = new DateTimeZone($candidate);
            $candidateOffset = $candidateTz->getOffset($now);
            $diff = abs($offset - $candidateOffset);

            if ($diff < $bestDiff) {
                $bestDiff = $diff;
                $bestMatch = $candidate;
            }

            if ($diff === 0) {
                break;
            }
        }

        return $bestMatch;
    }
}
