<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Data\Contact;

use Toddstoker\KeapSdk\Enums\Contact\AddressField;

/**
 * Address Data Transfer Object
 *
 * Represents a physical address associated with a contact.
 */
readonly class Address
{
    public function __construct(
        public AddressField $field,
        public ?string $line1 = null,
        public ?string $line2 = null,
        public ?string $locality = null,
        public ?string $region = null,
        public ?string $regionCode = null,
        public ?string $postalCode = null,
        public ?string $zipCode = null,
        public ?string $zipFour = null,
        public ?string $country = null,
        public ?string $countryCode = null,
    ) {
    }

    /**
     * Create an Address from an array of data
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            field: isset($data['field']) ? AddressField::from($data['field']) : AddressField::ADDRESS_FIELD_UNSPECIFIED,
            line1: $data['line1'] ?? null,
            line2: $data['line2'] ?? null,
            locality: $data['locality'] ?? null,
            region: $data['region'] ?? null,
            regionCode: $data['region_code'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            zipCode: $data['zip_code'] ?? null,
            zipFour: $data['zip_four'] ?? null,
            country: $data['country'] ?? null,
            countryCode: $data['country_code'] ?? null,
        );
    }

    /**
     * Convert the Address to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'field' => $this->field->value,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'locality' => $this->locality,
            'region' => $this->region,
            'region_code' => $this->regionCode,
            'postal_code' => $this->postalCode,
            'zip_code' => $this->zipCode,
            'zip_four' => $this->zipFour,
            'country' => $this->country,
            'country_code' => $this->countryCode,
        ], fn($value) => $value !== null && $value !== '');
    }
}
