<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Data\Contact;

use Toddstoker\KeapSdk\Enums\Contact\EmailField;
use Toddstoker\KeapSdk\Enums\Contact\EmailOptStatus;

/**
 * Contact Email Address Data Transfer Object
 *
 * Represents an email address associated with a contact.
 * Contacts can have up to 3 email addresses.
 */
readonly class EmailAddress
{
    public function __construct(
        public string $email,
        public EmailField $field,
        public ?string $optInReason = null,
        public bool $isOptIn = false,
        public ?EmailOptStatus $emailOptStatus = null,
    ) {
    }

    /**
     * Create a ContactEmailAddress from an array of data
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'] ?? '',
            field: isset($data['field']) ? EmailField::from($data['field']) : EmailField::EMAIL_FIELD_UNSPECIFIED,
            optInReason: $data['opt_in_reason'] ?? null,
            isOptIn: $data['is_opt_in'] ?? false,
            emailOptStatus: isset($data['email_opt_status']) ? EmailOptStatus::from($data['email_opt_status']) : null,
        );
    }

    /**
     * Convert the ContactEmailAddress to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'email' => $this->email,
            'field' => $this->field->value,
            'opt_in_reason' => $this->optInReason,
            'is_opt_in' => $this->isOptIn,
            'email_opt_status' => $this->emailOptStatus?->value,
        ], fn($value) => $value !== null && $value !== '');
    }
}