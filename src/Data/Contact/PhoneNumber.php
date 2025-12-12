<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Data\Contact;

use Toddstoker\KeapSdk\Enums\Contact\PhoneField;

/**
 * Phone Number Data Transfer Object
 *
 * Represents a phone number associated with a contact.
 * Contacts can have up to 5 phone numbers.
 */
readonly class PhoneNumber
{
    public function __construct(
        public string $number,
        public PhoneField $field,
        public ?string $extension = null,
        public ?string $type = null,
        public ?string $numberE164 = null,
    ) {
    }

    /**
     * Create a PhoneNumber from an array of data
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            number: $data['number'] ?? '',
            field: isset($data['field']) ? PhoneField::from($data['field']) : PhoneField::PHONE_NUMBER_FIELD_UNSPECIFIED,
            extension: $data['extension'] ?? null,
            type: $data['type'] ?? null,
            numberE164: $data['number_e164'] ?? null,
        );
    }

    /**
     * Convert the PhoneNumber to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'number' => $this->number,
            'field' => $this->field->value,
            'extension' => $this->extension,
            'type' => $this->type,
            'number_e164' => $this->numberE164,
        ], fn($value) => $value !== null && $value !== '');
    }
}
