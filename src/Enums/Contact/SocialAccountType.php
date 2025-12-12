<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Enums\Contact;

/**
 * Social media account types for contacts
 *
 * @see https://developer.keap.com/docs/restv2/
 */
enum SocialAccountType: string
{
    case SOCIAL_ACCOUNT_TYPE_UNSPECIFIED = 'SOCIAL_ACCOUNT_TYPE_UNSPECIFIED';
    case FACEBOOK = 'FACEBOOK';
    case LINKED_IN = 'LINKED_IN';
    case TWITTER = 'TWITTER';
    case INSTAGRAM = 'INSTAGRAM';
    case SNAPCHAT = 'SNAPCHAT';
    case YOUTUBE = 'YOUTUBE';
    case PINTEREST = 'PINTEREST';

    /**
     * Get the platform's base URL
     */
    public function platformUrl(): ?string
    {
        return match ($this) {
            self::FACEBOOK => 'https://facebook.com/',
            self::LINKED_IN => 'https://linkedin.com/in/',
            self::TWITTER => 'https://twitter.com/',
            self::INSTAGRAM => 'https://instagram.com/',
            self::SNAPCHAT => 'https://snapchat.com/add/',
            self::YOUTUBE => 'https://youtube.com/@',
            self::PINTEREST => 'https://pinterest.com/',
            default => null,
        };
    }

    /**
     * Get a human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::SOCIAL_ACCOUNT_TYPE_UNSPECIFIED => 'Unspecified',
            self::FACEBOOK => 'Facebook',
            self::LINKED_IN => 'LinkedIn',
            self::TWITTER => 'Twitter',
            self::INSTAGRAM => 'Instagram',
            self::SNAPCHAT => 'Snapchat',
            self::YOUTUBE => 'YouTube',
            self::PINTEREST => 'Pinterest',
        };
    }
}
