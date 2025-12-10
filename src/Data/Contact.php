<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Data;

use DateTimeImmutable;

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
     * @param array<string, mixed>|null $addresses
     * @param array<string, mixed>|null $customFields
     * @param array<string, mixed>|null $emailAddresses
     * @param array<string, mixed>|null $phoneNumbers
     * @param array<string, mixed>|null $faxNumbers
     * @param array<string, mixed>|null $socialAccounts
     * @param array<int>|null $tagIds
     */
    public function __construct(
        public ?int $id = null,
        public ?string $givenName = null,
        public ?string $familyName = null,
        public ?string $middleName = null,
        public ?string $preferredName = null,
        public ?string $prefix = null,
        public ?string $suffix = null,
        public ?string $email = null,
        public ?string $emailStatus = null,
        public ?bool $emailOptedIn = null,
        public ?string $optInReason = null,
        public ?string $jobTitle = null,
        public ?string $companyName = null,
        public ?int $companyId = null,
        public ?string $contactType = null,
        public ?int $ownerId = null,
        public ?int $leadSourceId = null,
        public ?string $sourceType = null,
        public ?string $spouseName = null,
        public ?string $website = null,
        public ?string $preferredLocale = null,
        public ?string $timeZone = null,
        public ?DateTimeImmutable $birthday = null,
        public ?DateTimeImmutable $anniversary = null,
        public ?DateTimeImmutable $dateCreated = null,
        public ?DateTimeImmutable $lastUpdated = null,
        public ?array $addresses = null,
        public ?array $customFields = null,
        public ?array $emailAddresses = null,
        public ?array $phoneNumbers = null,
        public ?array $faxNumbers = null,
        public ?array $socialAccounts = null,
        public ?array $tagIds = null,
    ) {
    }

    /**
     * Create a Contact from an array of data
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $id = $data['id'] ?? null;
        if(is_string($id) && ctype_digit($id)) {
            $id = (int)$id;
        }

        return new self(
            id: $id,
            givenName: $data['given_name'] ?? null,
            familyName: $data['family_name'] ?? null,
            middleName: $data['middle_name'] ?? null,
            preferredName: $data['preferred_name'] ?? null,
            prefix: $data['prefix'] ?? null,
            suffix: $data['suffix'] ?? null,
            email: $data['email'] ?? ($data['email_addresses'][0]['email'] ?? null),
            emailStatus: $data['email_status'] ?? null,
            emailOptedIn: $data['email_opted_in'] ?? null,
            optInReason: $data['opt_in_reason'] ?? null,
            jobTitle: $data['job_title'] ?? null,
            companyName: $data['company_name'] ?? ($data['company']['company_name'] ?? null),
            companyId: $data['company']['id'] ?? null,
            contactType: $data['contact_type'] ?? null,
            ownerId: $data['owner_id'] ?? null,
            leadSourceId: $data['lead_source_id'] ?? null,
            sourceType: $data['source_type'] ?? null,
            spouseName: $data['spouse_name'] ?? null,
            website: $data['website'] ?? null,
            preferredLocale: $data['preferred_locale'] ?? null,
            timeZone: $data['time_zone'] ?? null,
            birthday: isset($data['birthday']) ? new DateTimeImmutable($data['birthday']) : null,
            anniversary: isset($data['anniversary']) ? new DateTimeImmutable($data['anniversary']) : null,
            dateCreated: isset($data['date_created']) ? new DateTimeImmutable($data['date_created']) : null,
            lastUpdated: isset($data['last_updated']) ? new DateTimeImmutable($data['last_updated']) : null,
            addresses: $data['addresses'] ?? null,
            customFields: $data['custom_fields'] ?? null,
            emailAddresses: $data['email_addresses'] ?? null,
            phoneNumbers: $data['phone_numbers'] ?? null,
            faxNumbers: $data['fax_numbers'] ?? null,
            socialAccounts: $data['social_accounts'] ?? null,
            tagIds: $data['tag_ids'] ?? null,
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
        if ($this->emailStatus !== null) {
            $data['email_status'] = $this->emailStatus;
        }
        if ($this->emailOptedIn !== null) {
            $data['email_opted_in'] = $this->emailOptedIn;
        }
        if ($this->optInReason !== null) {
            $data['opt_in_reason'] = $this->optInReason;
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
            $data['source_type'] = $this->sourceType;
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
        if ($this->addresses !== null) {
            $data['addresses'] = $this->addresses;
        }
        if ($this->customFields !== null) {
            $data['custom_fields'] = $this->customFields;
        }
        if ($this->emailAddresses !== null) {
            $data['email_addresses'] = $this->emailAddresses;
        }
        if ($this->phoneNumbers !== null) {
            $data['phone_numbers'] = $this->phoneNumbers;
        }
        if ($this->faxNumbers !== null) {
            $data['fax_numbers'] = $this->faxNumbers;
        }
        if ($this->socialAccounts !== null) {
            $data['social_accounts'] = $this->socialAccounts;
        }
        if ($this->tagIds !== null) {
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
