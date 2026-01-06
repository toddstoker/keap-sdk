<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support;

/**
 * Trait for converting legacy contact payloads to modern REST API format
 *
 * This trait provides common conversion logic for transforming legacy PascalCase
 * contact payloads back to v1 and v2 API snake_case formats.
 * Version-specific field mappings (dates, IDs, company structure) must be implemented by the using class.
 */
trait ConvertsFromLegacyPayload
{
    /**
     * Convert a legacy contact payload to API contact payload format
     *
     * @param  array<string, mixed>  $legacyPayload  The legacy contact payload with PascalCase field names
     * @param  array<string, array{name: string, type: string}>  $customFieldMap  Map of custom field IDs to field config
     *                                                                            Example: ['1' => ['name' => '_LegacyFieldName', 'type' => 'Text']]
     * @return array<string, mixed> API contact payload with snake_case field names
     */
    public static function convertLegacyToContactPayload(array $legacyPayload, array $customFieldMap = []): array
    {
        $apiPayload = [];

        static::mapLegacyBasicFields($legacyPayload, $apiPayload);
        static::mapLegacyDateFields($legacyPayload, $apiPayload);
        static::mapLegacyIdFields($legacyPayload, $apiPayload);
        static::mapLegacyCompanyFields($legacyPayload, $apiPayload);
        static::mapLegacyEmailFields($legacyPayload, $apiPayload);
        static::mapLegacyPhoneFields($legacyPayload, $apiPayload);
        static::mapLegacyFaxFields($legacyPayload, $apiPayload);
        static::mapLegacyAddressFields($legacyPayload, $apiPayload);
        static::mapLegacyCustomFields($legacyPayload, $customFieldMap, $apiPayload);
        static::mapLegacyTagFields($legacyPayload, $apiPayload);

        return $apiPayload;
    }

    /**
     * Map basic contact fields from PascalCase to snake_case
     *
     * @param  array<string, mixed>  $legacyPayload
     * @param  array<string, mixed>  $apiPayload
     */
    protected static function mapLegacyBasicFields(array $legacyPayload, array &$apiPayload): void
    {
        if (isset($legacyPayload['Id']) && $legacyPayload['Id'] !== '') {
            $apiPayload['id'] = $legacyPayload['Id'];
        }

        if (isset($legacyPayload['FirstName']) && $legacyPayload['FirstName'] !== '') {
            $apiPayload['given_name'] = $legacyPayload['FirstName'];
        }

        if (isset($legacyPayload['LastName']) && $legacyPayload['LastName'] !== '') {
            $apiPayload['family_name'] = $legacyPayload['LastName'];
        }

        if (isset($legacyPayload['MiddleName']) && $legacyPayload['MiddleName'] !== '') {
            $apiPayload['middle_name'] = $legacyPayload['MiddleName'];
        }

        if (isset($legacyPayload['Nickname']) && $legacyPayload['Nickname'] !== '') {
            $apiPayload['preferred_name'] = $legacyPayload['Nickname'];
        }

        if (isset($legacyPayload['Title']) && $legacyPayload['Title'] !== '') {
            $apiPayload['prefix'] = $legacyPayload['Title'];
        }

        if (isset($legacyPayload['Suffix']) && $legacyPayload['Suffix'] !== '') {
            $apiPayload['suffix'] = $legacyPayload['Suffix'];
        }

        if (isset($legacyPayload['SpouseName']) && $legacyPayload['SpouseName'] !== '') {
            $apiPayload['spouse_name'] = $legacyPayload['SpouseName'];
        }

        if (isset($legacyPayload['JobTitle']) && $legacyPayload['JobTitle'] !== '') {
            $apiPayload['job_title'] = $legacyPayload['JobTitle'];
        }

        if (isset($legacyPayload['Website']) && $legacyPayload['Website'] !== '') {
            $apiPayload['website'] = $legacyPayload['Website'];
        }

        if (isset($legacyPayload['TimeZone']) && $legacyPayload['TimeZone'] !== '') {
            $apiPayload['time_zone'] = $legacyPayload['TimeZone'];
        }

        if (isset($legacyPayload['Language']) && $legacyPayload['Language'] !== '') {
            $apiPayload['preferred_locale'] = $legacyPayload['Language'];
        }

        if (isset($legacyPayload['ContactType']) && $legacyPayload['ContactType'] !== '') {
            $apiPayload['contact_type'] = $legacyPayload['ContactType'];
        }
    }

    /**
     * Map date fields (version-specific implementation required)
     *
     * V1 target: birthday, anniversary, date_created, last_updated
     * V2 target: birth_date, anniversary_date, create_time, update_time
     *
     * @param  array<string, mixed>  $legacyPayload
     * @param  array<string, mixed>  $apiPayload
     */
    abstract protected static function mapLegacyDateFields(array $legacyPayload, array &$apiPayload): void;

    /**
     * Map ID fields (version-specific implementation required)
     *
     * V1 target: owner_id, lead_source_id (with underscore)
     * V2 target: owner_id, leadsource_id (no underscore)
     *
     * @param  array<string, mixed>  $legacyPayload
     * @param  array<string, mixed>  $apiPayload
     */
    abstract protected static function mapLegacyIdFields(array $legacyPayload, array &$apiPayload): void;

    /**
     * Map company fields (version-specific implementation required)
     *
     * V1 target: company_name (flat structure)
     * V2 target: company: {company_name, id} (nested structure)
     *
     * @param  array<string, mixed>  $legacyPayload
     * @param  array<string, mixed>  $apiPayload
     */
    abstract protected static function mapLegacyCompanyFields(array $legacyPayload, array &$apiPayload): void;

    /**
     * Map numbered email fields to email_addresses array
     *
     * @param  array<string, mixed>  $legacyPayload
     * @param  array<string, mixed>  $apiPayload
     */
    protected static function mapLegacyEmailFields(array $legacyPayload, array &$apiPayload): void
    {
        $emailAddresses = [];

        $emailMap = [
            'Email' => 'EMAIL1',
            'EmailAddress2' => 'EMAIL2',
            'EmailAddress3' => 'EMAIL3',
        ];

        foreach ($emailMap as $legacyKey => $fieldKey) {
            if (isset($legacyPayload[$legacyKey]) && $legacyPayload[$legacyKey] !== '') {
                $emailAddresses[] = [
                    'email' => $legacyPayload[$legacyKey],
                    'field' => $fieldKey,
                ];
            }
        }

        if (! empty($emailAddresses)) {
            $apiPayload['email_addresses'] = $emailAddresses;
        }
    }

    /**
     * Map numbered phone fields to phone_numbers array
     *
     * @param  array<string, mixed>  $legacyPayload
     * @param  array<string, mixed>  $apiPayload
     */
    protected static function mapLegacyPhoneFields(array $legacyPayload, array &$apiPayload): void
    {
        $phoneNumbers = [];

        $phoneMap = [
            'PHONE1' => ['number' => 'Phone1', 'ext' => 'Phone1Ext', 'type' => 'Phone1Type'],
            'PHONE2' => ['number' => 'Phone2', 'ext' => 'Phone2Ext', 'type' => 'Phone2Type'],
            'PHONE3' => ['number' => 'Phone3', 'ext' => 'Phone3Ext', 'type' => 'Phone3Type'],
            'PHONE4' => ['number' => 'Phone4', 'ext' => 'Phone4Ext', 'type' => 'Phone4Type'],
            'PHONE5' => ['number' => 'Phone5', 'ext' => 'Phone5Ext', 'type' => 'Phone5Type'],
        ];

        foreach ($phoneMap as $fieldKey => $mapping) {
            if (isset($legacyPayload[$mapping['number']]) && $legacyPayload[$mapping['number']] !== '') {
                $phone = [
                    'number' => $legacyPayload[$mapping['number']],
                    'field' => $fieldKey,
                ];

                if (isset($legacyPayload[$mapping['ext']]) && $legacyPayload[$mapping['ext']] !== '') {
                    $phone['extension'] = $legacyPayload[$mapping['ext']];
                }

                if (isset($legacyPayload[$mapping['type']]) && $legacyPayload[$mapping['type']] !== '') {
                    $phone['type'] = $legacyPayload[$mapping['type']];
                }

                $phoneNumbers[] = $phone;
            }
        }

        if (! empty($phoneNumbers)) {
            $apiPayload['phone_numbers'] = $phoneNumbers;
        }
    }

    /**
     * Map numbered fax fields to fax_numbers array
     *
     * @param  array<string, mixed>  $legacyPayload
     * @param  array<string, mixed>  $apiPayload
     */
    protected static function mapLegacyFaxFields(array $legacyPayload, array &$apiPayload): void
    {
        $faxNumbers = [];

        $faxMap = [
            'FAX1' => ['number' => 'Fax1', 'type' => 'Fax1Type'],
            'FAX2' => ['number' => 'Fax2', 'type' => 'Fax2Type'],
        ];

        foreach ($faxMap as $fieldKey => $mapping) {
            if (isset($legacyPayload[$mapping['number']]) && $legacyPayload[$mapping['number']] !== '') {
                $fax = [
                    'number' => $legacyPayload[$mapping['number']],
                    'field' => $fieldKey,
                ];

                if (isset($legacyPayload[$mapping['type']]) && $legacyPayload[$mapping['type']] !== '') {
                    $fax['type'] = $legacyPayload[$mapping['type']];
                }

                $faxNumbers[] = $fax;
            }
        }

        if (! empty($faxNumbers)) {
            $apiPayload['fax_numbers'] = $faxNumbers;
        }
    }

    /**
     * Map numbered address blocks to addresses array
     *
     * Legacy format supports up to 3 addresses with different field naming patterns:
     * - First: StreetAddress1/2, City, State, PostalCode, Country, ZipFour1, Address1Type
     * - Second: Address2Street1/2, City2, State2, PostalCode2, Country2, ZipFour2, Address2Type
     * - Third: Address3Street1/2, City3, State3, PostalCode3, Country3, ZipFour3, Address3Type
     *
     * @param  array<string, mixed>  $legacyPayload
     * @param  array<string, mixed>  $apiPayload
     */
    protected static function mapLegacyAddressFields(array $legacyPayload, array &$apiPayload): void
    {
        $addresses = [];

        // First address (base field names)
        $address1 = [];

        if (isset($legacyPayload['StreetAddress1']) && $legacyPayload['StreetAddress1'] !== '') {
            $address1['line1'] = $legacyPayload['StreetAddress1'];
        }

        if (isset($legacyPayload['StreetAddress2']) && $legacyPayload['StreetAddress2'] !== '') {
            $address1['line2'] = $legacyPayload['StreetAddress2'];
        }

        if (isset($legacyPayload['City']) && $legacyPayload['City'] !== '') {
            $address1['locality'] = $legacyPayload['City'];
        }

        if (isset($legacyPayload['State']) && $legacyPayload['State'] !== '') {
            $address1['region'] = $legacyPayload['State'];
        }

        if (isset($legacyPayload['PostalCode']) && $legacyPayload['PostalCode'] !== '') {
            $address1['postal_code'] = $legacyPayload['PostalCode'];
        }

        if (isset($legacyPayload['Country']) && $legacyPayload['Country'] !== '') {
            $address1['country_code'] = $legacyPayload['Country'];
        }

        if (isset($legacyPayload['ZipFour1']) && $legacyPayload['ZipFour1'] !== '') {
            $address1['zip_four'] = $legacyPayload['ZipFour1'];
        }

        if (isset($legacyPayload['Address1Type']) && $legacyPayload['Address1Type'] !== '') {
            $address1['field'] = $legacyPayload['Address1Type'];
        } elseif (! empty($address1)) {
            $address1['field'] = 'BILLING'; // Default field type
        }

        if (! empty($address1)) {
            $addresses[] = $address1;
        }

        // Second address (numbered fields)
        $address2 = [];

        if (isset($legacyPayload['Address2Street1']) && $legacyPayload['Address2Street1'] !== '') {
            $address2['line1'] = $legacyPayload['Address2Street1'];
        }

        if (isset($legacyPayload['Address2Street2']) && $legacyPayload['Address2Street2'] !== '') {
            $address2['line2'] = $legacyPayload['Address2Street2'];
        }

        if (isset($legacyPayload['City2']) && $legacyPayload['City2'] !== '') {
            $address2['locality'] = $legacyPayload['City2'];
        }

        if (isset($legacyPayload['State2']) && $legacyPayload['State2'] !== '') {
            $address2['region'] = $legacyPayload['State2'];
        }

        if (isset($legacyPayload['PostalCode2']) && $legacyPayload['PostalCode2'] !== '') {
            $address2['postal_code'] = $legacyPayload['PostalCode2'];
        }

        if (isset($legacyPayload['Country2']) && $legacyPayload['Country2'] !== '') {
            $address2['country_code'] = $legacyPayload['Country2'];
        }

        if (isset($legacyPayload['ZipFour2']) && $legacyPayload['ZipFour2'] !== '') {
            $address2['zip_four'] = $legacyPayload['ZipFour2'];
        }

        if (isset($legacyPayload['Address2Type']) && $legacyPayload['Address2Type'] !== '') {
            $address2['field'] = $legacyPayload['Address2Type'];
        } elseif (! empty($address2)) {
            $address2['field'] = 'SHIPPING'; // Default field type
        }

        if (! empty($address2)) {
            $addresses[] = $address2;
        }

        // Third address (numbered fields)
        $address3 = [];

        if (isset($legacyPayload['Address3Street1']) && $legacyPayload['Address3Street1'] !== '') {
            $address3['line1'] = $legacyPayload['Address3Street1'];
        }

        if (isset($legacyPayload['Address3Street2']) && $legacyPayload['Address3Street2'] !== '') {
            $address3['line2'] = $legacyPayload['Address3Street2'];
        }

        if (isset($legacyPayload['City3']) && $legacyPayload['City3'] !== '') {
            $address3['locality'] = $legacyPayload['City3'];
        }

        if (isset($legacyPayload['State3']) && $legacyPayload['State3'] !== '') {
            $address3['region'] = $legacyPayload['State3'];
        }

        if (isset($legacyPayload['PostalCode3']) && $legacyPayload['PostalCode3'] !== '') {
            $address3['postal_code'] = $legacyPayload['PostalCode3'];
        }

        if (isset($legacyPayload['Country3']) && $legacyPayload['Country3'] !== '') {
            $address3['country_code'] = $legacyPayload['Country3'];
        }

        if (isset($legacyPayload['ZipFour3']) && $legacyPayload['ZipFour3'] !== '') {
            $address3['zip_four'] = $legacyPayload['ZipFour3'];
        }

        if (isset($legacyPayload['Address3Type']) && $legacyPayload['Address3Type'] !== '') {
            $address3['field'] = $legacyPayload['Address3Type'];
        } elseif (! empty($address3)) {
            $address3['field'] = 'OTHER'; // Default field type
        }

        if (! empty($address3)) {
            $addresses[] = $address3;
        }

        if (! empty($addresses)) {
            $apiPayload['addresses'] = $addresses;
        }
    }

    /**
     * Map custom fields using reverse lookup from customFieldMap
     *
     * @param  array<string, mixed>  $legacyPayload  The legacy contact payload
     * @param  array<string, array{name: string, type: string}>  $customFieldMap  Map of custom field IDs to field config
     * @param  array<string, mixed>  $apiPayload  The API payload being built
     */
    protected static function mapLegacyCustomFields(array $legacyPayload, array $customFieldMap, array &$apiPayload): void
    {
        // Create reverse lookup: field name => field ID
        $reverseMap = [];
        foreach ($customFieldMap as $id => $config) {
            $reverseMap[$config['name']] = $id;
        }

        $customFields = [];

        foreach ($legacyPayload as $key => $value) {
            // Skip if value is empty
            if ($value === '' || $value === null) {
                continue;
            }

            $fieldId = null;

            // Check if key is in reverse map
            if (isset($reverseMap[$key])) {
                $fieldId = $reverseMap[$key];
            }
            // Or if it matches _CustomField{id} pattern
            elseif (preg_match('/^_CustomField(\d+)$/', $key, $matches)) {
                $fieldId = $matches[1];
            }

            if ($fieldId !== null) {
                $customFields[] = [
                    'id' => (int) $fieldId,
                    'content' => $value,
                ];
            }
        }

        if (! empty($customFields)) {
            $apiPayload['custom_fields'] = $customFields;
        }
    }

    /**
     * Map Groups to tag_ids
     *
     * @param  array<string, mixed>  $legacyPayload
     * @param  array<string, mixed>  $apiPayload
     */
    protected static function mapLegacyTagFields(array $legacyPayload, array &$apiPayload): void
    {
        if (isset($legacyPayload['Groups']) && is_array($legacyPayload['Groups']) && ! empty($legacyPayload['Groups'])) {
            $apiPayload['tag_ids'] = $legacyPayload['Groups'];
        }
    }

    /**
     * Convert a date value to string format
     *
     * @param  \DateTimeImmutable|string|null  $date  The date value (can be object or string)
     * @param  string  $format  PHP date format string
     * @return string|null The formatted date string or null
     */
    protected static function dateToString(\DateTimeImmutable|string|null $date, string $format = 'Y-m-d\TH:i:s\Z'): ?string
    {
        if ($date === null) {
            return null;
        }

        if (is_string($date)) {
            return $date; // Already a string
        }

        return $date->format($format);
    }
}
