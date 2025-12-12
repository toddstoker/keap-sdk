<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Data\Contact;

use Toddstoker\KeapSdk\Enums\Contact\FaxField;

/**
 * Fax Number Data Transfer Object
 *
 * Represents a fax number associated with a contact.
 * Contacts can have up to 2 fax numbers.
 */
readonly class FaxNumber
{
    public function __construct(
        public string $number,
        public FaxField $field,
        public ?string $type = null,
    ) {
    }

    /**
     * Create a FaxNumber from an array of data
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            number: $data['number'] ?? '',
            field: isset($data['field']) ? FaxField::from($data['field']) : FaxField::FAX_NUMBER_FIELD_UNSPECIFIED,
            type: $data['type'] ?? null,
        );
    }

    /**
     * Convert the FaxNumber to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'number' => $this->number,
            'field' => $this->field->value,
            'type' => $this->type,
        ], fn($value) => $value !== null && $value !== '');
    }
}
