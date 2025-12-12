<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Enums\Contact;

/**
 * Email field types for contact email addresses
 *
 * Contacts can have up to 3 email addresses (EMAIL1, EMAIL2, EMAIL3).
 *
 * @see https://developer.keap.com/docs/restv2/
 */
enum EmailField: string
{
    case EMAIL_FIELD_UNSPECIFIED = 'EMAIL_FIELD_UNSPECIFIED';
    case EMAIL1 = 'EMAIL1';
    case EMAIL2 = 'EMAIL2';
    case EMAIL3 = 'EMAIL3';

    /**
     * Check if this is the primary email field
     */
    public function isPrimary(): bool
    {
        return $this === self::EMAIL1;
    }

    /**
     * Get the field number (1-3)
     */
    public function number(): ?int
    {
        return match ($this) {
            self::EMAIL1 => 1,
            self::EMAIL2 => 2,
            self::EMAIL3 => 3,
            default => null,
        };
    }

    /**
     * Get a human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::EMAIL_FIELD_UNSPECIFIED => 'Unspecified',
            self::EMAIL1 => 'Email 1 (Primary)',
            self::EMAIL2 => 'Email 2',
            self::EMAIL3 => 'Email 3',
        };
    }
}
