<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Enums\Contact;

/**
 * Email opt-in status for contact email addresses
 *
 * Represents the current email marketing permission status for a contact.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
enum EmailOptStatus: string
{
    case UNENGAGED_MARKETABLE = 'UNENGAGED_MARKETABLE';
    case SINGLE_OPT_IN = 'SINGLE_OPT_IN';
    case DOUBLE_OPT_IN = 'DOUBLE_OPT_IN';
    case CONFIRMED = 'CONFIRMED';
    case UNENGAGED_NON_MARKETABLE = 'UNENGAGED_NON_MARKETABLE';
    case NON_MARKETABLE = 'NON_MARKETABLE';
    case LOCKDOWN = 'LOCKDOWN';
    case BOUNCE = 'BOUNCE';
    case HARD_BOUNCE = 'HARD_BOUNCE';
    case MANUAL = 'MANUAL';
    case ADMIN = 'ADMIN';
    case SYSTEM = 'SYSTEM';
    case LIST_UNSUBSCRIBE = 'LIST_UNSUBSCRIBE';
    case FEEDBACK = 'FEEDBACK';
    case SPAM = 'SPAM';
    case INVALID = 'INVALID';
    case DEACTIVATED = 'DEACTIVATED';

    /**
     * Check if the email address is marketable
     */
    public function isMarketable(): bool
    {
        return match ($this) {
            self::UNENGAGED_MARKETABLE,
            self::SINGLE_OPT_IN,
            self::DOUBLE_OPT_IN,
            self::CONFIRMED => true,
            default => false,
        };
    }

    /**
     * Check if the email address has bounced
     */
    public function hasBounced(): bool
    {
        return match ($this) {
            self::BOUNCE,
            self::HARD_BOUNCE => true,
            default => false,
        };
    }

    /**
     * Get a human-readable label for the status
     */
    public function label(): string
    {
        return match ($this) {
            self::UNENGAGED_MARKETABLE => 'Unengaged Marketable',
            self::SINGLE_OPT_IN => 'Single Opt-In',
            self::DOUBLE_OPT_IN => 'Double Opt-In',
            self::CONFIRMED => 'Confirmed',
            self::UNENGAGED_NON_MARKETABLE => 'Unengaged Non-Marketable',
            self::NON_MARKETABLE => 'Non-Marketable',
            self::LOCKDOWN => 'Lockdown',
            self::BOUNCE => 'Bounce',
            self::HARD_BOUNCE => 'Hard Bounce',
            self::MANUAL => 'Manual Unsubscribe',
            self::ADMIN => 'Admin Unsubscribe',
            self::SYSTEM => 'System Unsubscribe',
            self::LIST_UNSUBSCRIBE => 'List Unsubscribe',
            self::FEEDBACK => 'Feedback',
            self::SPAM => 'Marked as Spam',
            self::INVALID => 'Invalid Email',
            self::DEACTIVATED => 'Deactivated',
        };
    }
}
