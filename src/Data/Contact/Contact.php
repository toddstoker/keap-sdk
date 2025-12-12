<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Data\Contact;

use DateTimeImmutable;
use Toddstoker\KeapSdk\Data\Utility;
use Toddstoker\KeapSdk\Enums\Contact\SourceType;

/**
 * Contact Data Transfer Object
 *
 * Represents a contact in the Keap system with type-safe properties.
 *
 * @property-read int|null $id Contact ID
 * @property-read string|null $givenName First name
 * @property-read string|null $familyName Last name
 * @property-read string|null $email Primary email address
 */
readonly class Contact
{
    /**
     * @param array<EmailAddress> $emailAddresses
     * @param array<PhoneNumber> $phoneNumbers
     * @param array<Address> $addresses
     * @param array<FaxNumber> $faxNumbers
     * @param array<SocialAccount> $socialAccounts
     * @param array<string, mixed>|null $customFields
     * @param array<int> $tagIds
     */
    public function __construct(
        public ?int $id = null,
        public ?string $givenName = null,
        public ?string $familyName = null,
        public ?string $middleName = null,
        public ?string $preferredName = null,
        public ?string $prefix = null,
        public ?string $suffix = null,
        public ?string $jobTitle = null,
        public ?string $companyName = null,
        public ?int $companyId = null,
        public ?string $contactType = null,
        public ?int $ownerId = null,
        public ?int $leadSourceId = null,
        public ?SourceType $sourceType = null,
        public ?string $spouseName = null,
        public ?string $website = null,
        public ?string $preferredLocale = null,
        public ?string $timeZone = null,
        public ?DateTimeImmutable $birthday = null,
        public ?DateTimeImmutable $anniversary = null,
        public ?DateTimeImmutable $dateCreated = null,
        public ?DateTimeImmutable $lastUpdated = null,
        public array $emailAddresses = [],
        public array $phoneNumbers = [],
        public array $addresses = [],
        public array $faxNumbers = [],
        public array $socialAccounts = [],
        public ?array $customFields = null,
        public array $tagIds = [],
    ) {
    }

    /**
     * Create a Contact from an array of data
     *
     * @param array<string, mixed> $data
     * @throws \DateMalformedStringException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: Utility::stringToInteger($data['id'] ?? null),
            givenName: $data['given_name'] ?? null,
            familyName: $data['family_name'] ?? null,
            middleName: $data['middle_name'] ?? null,
            preferredName: $data['preferred_name'] ?? null,
            prefix: $data['prefix'] ?? null,
            suffix: $data['suffix'] ?? null,
            jobTitle: $data['job_title'] ?? null,
            companyName: $data['company_name'] ?? ($data['company']['company_name'] ?? null),
            companyId: Utility::stringToInteger($data['company']['id'] ?? null),
            contactType: $data['contact_type'] ?? null,
            ownerId: Utility::stringToInteger($data['owner_id'] ?? null),
            leadSourceId: Utility::stringToInteger($data['lead_source_id'] ?? null),
            sourceType: isset($data['source_type']) ? SourceType::from($data['source_type']) : null,
            spouseName: $data['spouse_name'] ?? null,
            website: $data['website'] ?? null,
            preferredLocale: $data['preferred_locale'] ?? null,
            timeZone: $data['time_zone'] ?? null,
            birthday: isset($data['birthday']) ? new DateTimeImmutable($data['birthday']) : null,
            anniversary: isset($data['anniversary']) ? new DateTimeImmutable($data['anniversary']) : null,
            dateCreated: isset($data['date_created']) ? new DateTimeImmutable($data['date_created']) : null,
            lastUpdated: isset($data['last_updated']) ? new DateTimeImmutable($data['last_updated']) : null,
            emailAddresses: array_map(fn($item) => EmailAddress::fromArray($item), $data['email_addresses'] ?? []),
            phoneNumbers: array_map(fn($item) => PhoneNumber::fromArray($item), $data['phone_numbers'] ?? []),
            addresses: array_map(fn($item) => Address::fromArray($item), $data['addresses'] ?? []),
            faxNumbers: array_map(fn($item) => FaxNumber::fromArray($item), $data['fax_numbers'] ?? []),
            socialAccounts: array_map(fn($item) => SocialAccount::fromArray($item), $data['social_accounts'] ?? []),
            customFields: $data['custom_fields'] ?? null,
            tagIds: $data['tag_ids'] ?? [],
        );
    }

    /**
     * Convert the Contact to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }
        if ($this->givenName !== null) {
            $data['given_name'] = $this->givenName;
        }
        if ($this->familyName !== null) {
            $data['family_name'] = $this->familyName;
        }
        if ($this->middleName !== null) {
            $data['middle_name'] = $this->middleName;
        }
        if ($this->preferredName !== null) {
            $data['preferred_name'] = $this->preferredName;
        }
        if ($this->prefix !== null) {
            $data['prefix'] = $this->prefix;
        }
        if ($this->suffix !== null) {
            $data['suffix'] = $this->suffix;
        }
        if ($this->jobTitle !== null) {
            $data['job_title'] = $this->jobTitle;
        }
        if ($this->companyName !== null || $this->companyId !== null) {
            $data['company'] = array_filter([
                'id' => $this->companyId,
                'company_name' => $this->companyName,
            ]);
        }
        if ($this->contactType !== null) {
            $data['contact_type'] = $this->contactType;
        }
        if ($this->ownerId !== null) {
            $data['owner_id'] = $this->ownerId;
        }
        if ($this->leadSourceId !== null) {
            $data['lead_source_id'] = $this->leadSourceId;
        }
        if ($this->sourceType !== null) {
            $data['source_type'] = $this->sourceType->value;
        }
        if ($this->spouseName !== null) {
            $data['spouse_name'] = $this->spouseName;
        }
        if ($this->website !== null) {
            $data['website'] = $this->website;
        }
        if ($this->preferredLocale !== null) {
            $data['preferred_locale'] = $this->preferredLocale;
        }
        if ($this->timeZone !== null) {
            $data['time_zone'] = $this->timeZone;
        }
        if ($this->birthday !== null) {
            $data['birthday'] = $this->birthday->format('Y-m-d');
        }
        if ($this->anniversary !== null) {
            $data['anniversary'] = $this->anniversary->format('Y-m-d');
        }
        if (!empty($this->emailAddresses)) {
            $data['email_addresses'] = array_map(fn($item) => $item->toArray(), $this->emailAddresses);
        }
        if (!empty($this->phoneNumbers)) {
            $data['phone_numbers'] = array_map(fn($item) => $item->toArray(), $this->phoneNumbers);
        }
        if (!empty($this->addresses)) {
            $data['addresses'] = array_map(fn($item) => $item->toArray(), $this->addresses);
        }
        if (!empty($this->faxNumbers)) {
            $data['fax_numbers'] = array_map(fn($item) => $item->toArray(), $this->faxNumbers);
        }
        if (!empty($this->socialAccounts)) {
            $data['social_accounts'] = array_map(fn($item) => $item->toArray(), $this->socialAccounts);
        }
        if ($this->customFields !== null) {
            $data['custom_fields'] = $this->customFields;
        }
        if (!empty($this->tagIds)) {
            $data['tag_ids'] = $this->tagIds;
        }

        return $data;
    }

    /**
     * Get the contact's full name
     */
    public function getFullName(): string
    {
        return trim(implode(' ', array_filter([
            $this->prefix,
            $this->givenName,
            $this->middleName,
            $this->familyName,
            $this->suffix,
        ])));
    }
}
