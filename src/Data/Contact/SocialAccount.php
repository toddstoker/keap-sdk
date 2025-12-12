<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Data\Contact;

use Toddstoker\KeapSdk\Enums\Contact\SocialAccountType;

/**
 * Social Account Data Transfer Object
 *
 * Represents a social media account associated with a contact.
 */
readonly class SocialAccount
{
    public function __construct(
        public SocialAccountType $type,
        public ?string $name = null,
    ) {
    }

    /**
     * Create a SocialAccount from an array of data
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: isset($data['type']) ? SocialAccountType::from($data['type']) : SocialAccountType::SOCIAL_ACCOUNT_TYPE_UNSPECIFIED,
            name: $data['name'] ?? null,
        );
    }

    /**
     * Convert the SocialAccount to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type->value,
            'name' => $this->name,
        ], fn($value) => $value !== null && $value !== '');
    }

    /**
     * Get the full URL to the social profile
     */
    public function getProfileUrl(): ?string
    {
        if ($this->name === null || $this->type->platformUrl() === null) {
            return null;
        }

        return $this->type->platformUrl() . $this->name;
    }
}
