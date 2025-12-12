<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Enums\Contact;

/**
 * Fax field types for contact fax numbers
 *
 * Contacts can have up to 2 fax numbers (FAX1, FAX2).
 *
 * @see https://developer.keap.com/docs/restv2/
 */
enum FaxField: string
{
    case FAX_NUMBER_FIELD_UNSPECIFIED = 'FAX_NUMBER_FIELD_UNSPECIFIED';
    case FAX1 = 'FAX1';
    case FAX2 = 'FAX2';

    /**
     * Check if this is the primary fax field
     */
    public function isPrimary(): bool
    {
        return $this === self::FAX1;
    }

    /**
     * Get the field number (1-2)
     */
    public function number(): ?int
    {
        return match ($this) {
            self::FAX1 => 1,
            self::FAX2 => 2,
            default => null,
        };
    }

    /**
     * Get a human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::FAX_NUMBER_FIELD_UNSPECIFIED => 'Unspecified',
            self::FAX1 => 'Fax 1 (Primary)',
            self::FAX2 => 'Fax 2',
        };
    }
}
