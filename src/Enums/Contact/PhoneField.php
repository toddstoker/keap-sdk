<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Enums\Contact;

/**
 * Phone field types for contact phone numbers
 *
 * Contacts can have up to 5 phone numbers (PHONE1 through PHONE5).
 *
 * @see https://developer.keap.com/docs/restv2/
 */
enum PhoneField: string
{
    case PHONE_NUMBER_FIELD_UNSPECIFIED = 'PHONE_NUMBER_FIELD_UNSPECIFIED';
    case PHONE1 = 'PHONE1';
    case PHONE2 = 'PHONE2';
    case PHONE3 = 'PHONE3';
    case PHONE4 = 'PHONE4';
    case PHONE5 = 'PHONE5';

    /**
     * Check if this is the primary phone field
     */
    public function isPrimary(): bool
    {
        return $this === self::PHONE1;
    }

    /**
     * Get the field number (1-5)
     */
    public function number(): ?int
    {
        return match ($this) {
            self::PHONE1 => 1,
            self::PHONE2 => 2,
            self::PHONE3 => 3,
            self::PHONE4 => 4,
            self::PHONE5 => 5,
            default => null,
        };
    }

    /**
     * Get a human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::PHONE_NUMBER_FIELD_UNSPECIFIED => 'Unspecified',
            self::PHONE1 => 'Phone 1 (Primary)',
            self::PHONE2 => 'Phone 2',
            self::PHONE3 => 'Phone 3',
            self::PHONE4 => 'Phone 4',
            self::PHONE5 => 'Phone 5',
        };
    }
}
