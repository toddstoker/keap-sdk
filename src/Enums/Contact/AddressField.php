<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Enums\Contact;

/**
 * Address field types for contact addresses
 *
 * Indicates the purpose or type of the address.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
enum AddressField: string
{
    case ADDRESS_FIELD_UNSPECIFIED = 'ADDRESS_FIELD_UNSPECIFIED';
    case BILLING = 'BILLING';
    case SHIPPING = 'SHIPPING';
    case OTHER = 'OTHER';

    /**
     * Check if this is a billing address
     */
    public function isBilling(): bool
    {
        return $this === self::BILLING;
    }

    /**
     * Check if this is a shipping address
     */
    public function isShipping(): bool
    {
        return $this === self::SHIPPING;
    }

    /**
     * Get a human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::ADDRESS_FIELD_UNSPECIFIED => 'Unspecified',
            self::BILLING => 'Billing Address',
            self::SHIPPING => 'Shipping Address',
            self::OTHER => 'Other Address',
        };
    }
}
