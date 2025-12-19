<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support;

/**
 * Trait for converting modern REST API contact payloads to legacy format
 *
 * This trait provides common conversion logic for both v1 and v2 API payloads.
 * Version-specific field mappings (dates, IDs) must be implemented by the using class.
 */
trait ConvertsToLegacyPayload
{
    /**
     * Convert a contact payload to legacy contact payload format
     *
     * @param  array  $contactPayload  The API contact payload (v1 or v2)
     * @param  array<string, array{name: string, type: string}>  $customFieldMap  Map of custom field IDs to field config
     *                                                                             Example: ['1' => ['name' => '_LegacyFieldName', 'type' => 'Text']]
     * @return array Legacy contact payload with PascalCase field names
     */
    public static function convertContactToLegacyPayload(array $contactPayload, array $customFieldMap = []): array
    {
        $legacyPayload = [];

        static::mapBasicFields($contactPayload, $legacyPayload);
        static::mapDateFields($contactPayload, $legacyPayload);
        static::mapIdFields($contactPayload, $legacyPayload);
        static::mapCompanyFields($contactPayload, $legacyPayload);
        static::mapEmailFields($contactPayload, $legacyPayload);
        static::mapPhoneFields($contactPayload, $legacyPayload);
        static::mapFaxFields($contactPayload, $legacyPayload);
        static::mapAddressFields($contactPayload, $legacyPayload);
        static::mapCustomFields($contactPayload, $customFieldMap, $legacyPayload);
        static::mapTags($contactPayload, $legacyPayload);

        return $legacyPayload;
    }

    /**
     * Map basic contact fields
     */
    protected static function mapBasicFields(array $payload, array &$legacyPayload): void
    {
        if (isset($payload['id'])) {
            $legacyPayload['Id'] = $payload['id'];
        }

        if (isset($payload['given_name'])) {
            $legacyPayload['FirstName'] = $payload['given_name'];
        }

        if (isset($payload['family_name'])) {
            $legacyPayload['LastName'] = $payload['family_name'];
        }

        if (isset($payload['middle_name'])) {
            $legacyPayload['MiddleName'] = $payload['middle_name'];
        }

        if (isset($payload['preferred_name'])) {
            $legacyPayload['Nickname'] = $payload['preferred_name'];
        }

        if (isset($payload['prefix'])) {
            $legacyPayload['Title'] = $payload['prefix'];
        }

        if (isset($payload['suffix'])) {
            $legacyPayload['Suffix'] = $payload['suffix'];
        }

        if (isset($payload['spouse_name'])) {
            $legacyPayload['SpouseName'] = $payload['spouse_name'];
        }

        if (isset($payload['job_title'])) {
            $legacyPayload['JobTitle'] = $payload['job_title'];
        }

        if (isset($payload['website'])) {
            $legacyPayload['Website'] = $payload['website'];
        }

        if (isset($payload['time_zone'])) {
            $legacyPayload['TimeZone'] = $payload['time_zone'];
        }

        if (isset($payload['preferred_locale'])) {
            $legacyPayload['Language'] = $payload['preferred_locale'];
        }

        if (isset($payload['contact_type'])) {
            $legacyPayload['ContactType'] = $payload['contact_type'];
        }
    }

    /**
     * Map date fields (version-specific implementation required)
     *
     * V1 uses: birthday, anniversary, date_created, last_updated
     * V2 uses: birth_date, anniversary_date, create_time, update_time
     */
    abstract protected static function mapDateFields(array $payload, array &$legacyPayload): void;

    /**
     * Map ID fields (version-specific implementation required)
     *
     * V1 uses: owner_id, lead_source_id
     * V2 uses: owner_id, leadsource_id (no underscore)
     */
    abstract protected static function mapIdFields(array $payload, array &$legacyPayload): void;

    /**
     * Map company fields
     */
    protected static function mapCompanyFields(array $payload, array &$legacyPayload): void
    {
        if (isset($payload['company']['company_name'])) {
            $legacyPayload['Company'] = $payload['company']['company_name'];
        } elseif (isset($payload['company_name'])) {
            $legacyPayload['Company'] = $payload['company_name'];
        }

        if (isset($payload['company']['id'])) {
            $legacyPayload['CompanyID'] = $payload['company']['id'];
        }
    }

    /**
     * Map email address array to numbered fields
     */
    protected static function mapEmailFields(array $payload, array &$legacyPayload): void
    {
        if (! isset($payload['email_addresses']) || ! is_array($payload['email_addresses'])) {
            return;
        }

        $emailMap = [
            'EMAIL1' => 'Email',
            'EMAIL2' => 'EmailAddress2',
            'EMAIL3' => 'EmailAddress3',
        ];

        foreach ($payload['email_addresses'] as $emailData) {
            if (isset($emailData['field']) && isset($emailMap[$emailData['field']]) && isset($emailData['email'])) {
                $legacyPayload[$emailMap[$emailData['field']]] = $emailData['email'];
            }
        }
    }

    /**
     * Map phone number array to numbered fields with extensions
     */
    protected static function mapPhoneFields(array $payload, array &$legacyPayload): void
    {
        if (! isset($payload['phone_numbers']) || ! is_array($payload['phone_numbers'])) {
            return;
        }

        $phoneMap = [
            'PHONE1' => ['number' => 'Phone1', 'ext' => 'Phone1Ext', 'type' => 'Phone1Type'],
            'PHONE2' => ['number' => 'Phone2', 'ext' => 'Phone2Ext', 'type' => 'Phone2Type'],
            'PHONE3' => ['number' => 'Phone3', 'ext' => 'Phone3Ext', 'type' => 'Phone3Type'],
            'PHONE4' => ['number' => 'Phone4', 'ext' => 'Phone4Ext', 'type' => 'Phone4Type'],
            'PHONE5' => ['number' => 'Phone5', 'ext' => 'Phone5Ext', 'type' => 'Phone5Type'],
        ];

        foreach ($payload['phone_numbers'] as $phoneData) {
            if (isset($phoneData['field']) && isset($phoneMap[$phoneData['field']])) {
                $mapping = $phoneMap[$phoneData['field']];

                if (isset($phoneData['number'])) {
                    $legacyPayload[$mapping['number']] = $phoneData['number'];
                }

                if (isset($phoneData['extension'])) {
                    $legacyPayload[$mapping['ext']] = $phoneData['extension'];
                }

                if (isset($phoneData['type'])) {
                    $legacyPayload[$mapping['type']] = $phoneData['type'];
                }
            }
        }
    }

    /**
     * Map fax number array to numbered fields
     */
    protected static function mapFaxFields(array $payload, array &$legacyPayload): void
    {
        if (! isset($payload['fax_numbers']) || ! is_array($payload['fax_numbers'])) {
            return;
        }

        $faxMap = [
            'FAX1' => ['number' => 'Fax1', 'type' => 'Fax1Type'],
            'FAX2' => ['number' => 'Fax2', 'type' => 'Fax2Type'],
        ];

        foreach ($payload['fax_numbers'] as $faxData) {
            if (isset($faxData['field']) && isset($faxMap[$faxData['field']])) {
                $mapping = $faxMap[$faxData['field']];

                if (isset($faxData['number'])) {
                    $legacyPayload[$mapping['number']] = $faxData['number'];
                }

                if (isset($faxData['type'])) {
                    $legacyPayload[$mapping['type']] = $faxData['type'];
                }
            }
        }
    }

    /**
     * Map address array to numbered fields
     *
     * Legacy format supports up to 3 addresses with numbered suffixes:
     * - First: StreetAddress1, City, State, PostalCode, Country, ZipFour1, Address1Type
     * - Second: Address2Street1, City2, State2, PostalCode2, Country2, ZipFour2, Address2Type
     * - Third: Address3Street1, City3, State3, PostalCode3, Country3, ZipFour3, Address3Type
     */
    protected static function mapAddressFields(array $payload, array &$legacyPayload): void
    {
        if (! isset($payload['addresses']) || ! is_array($payload['addresses'])) {
            return;
        }

        foreach ($payload['addresses'] as $index => $addressData) {
            if ($index === 0) {
                // First address uses base field names
                if (isset($addressData['line1'])) {
                    $legacyPayload['StreetAddress1'] = $addressData['line1'];
                }
                if (isset($addressData['line2'])) {
                    $legacyPayload['StreetAddress2'] = $addressData['line2'];
                }
                if (isset($addressData['locality'])) {
                    $legacyPayload['City'] = $addressData['locality'];
                }
                if (isset($addressData['region'])) {
                    $legacyPayload['State'] = $addressData['region'];
                }
                if (isset($addressData['postal_code'])) {
                    $legacyPayload['PostalCode'] = $addressData['postal_code'];
                } elseif (isset($addressData['zip_code'])) {
                    $legacyPayload['PostalCode'] = $addressData['zip_code'];
                }
                if (isset($addressData['country_code'])) {
                    $legacyPayload['Country'] = $addressData['country_code'];
                }
                if (isset($addressData['zip_four'])) {
                    $legacyPayload['ZipFour1'] = $addressData['zip_four'];
                }
                if (isset($addressData['field'])) {
                    $legacyPayload['Address1Type'] = $addressData['field'];
                }
            } elseif ($index === 1) {
                // Second address
                if (isset($addressData['line1'])) {
                    $legacyPayload['Address2Street1'] = $addressData['line1'];
                }
                if (isset($addressData['line2'])) {
                    $legacyPayload['Address2Street2'] = $addressData['line2'];
                }
                if (isset($addressData['locality'])) {
                    $legacyPayload['City2'] = $addressData['locality'];
                }
                if (isset($addressData['region'])) {
                    $legacyPayload['State2'] = $addressData['region'];
                }
                if (isset($addressData['postal_code'])) {
                    $legacyPayload['PostalCode2'] = $addressData['postal_code'];
                } elseif (isset($addressData['zip_code'])) {
                    $legacyPayload['PostalCode2'] = $addressData['zip_code'];
                }
                if (isset($addressData['country_code'])) {
                    $legacyPayload['Country2'] = $addressData['country_code'];
                }
                if (isset($addressData['zip_four'])) {
                    $legacyPayload['ZipFour2'] = $addressData['zip_four'];
                }
                if (isset($addressData['field'])) {
                    $legacyPayload['Address2Type'] = $addressData['field'];
                }
            } elseif ($index === 2) {
                // Third address
                if (isset($addressData['line1'])) {
                    $legacyPayload['Address3Street1'] = $addressData['line1'];
                }
                if (isset($addressData['line2'])) {
                    $legacyPayload['Address3Street2'] = $addressData['line2'];
                }
                if (isset($addressData['locality'])) {
                    $legacyPayload['City3'] = $addressData['locality'];
                }
                if (isset($addressData['region'])) {
                    $legacyPayload['State3'] = $addressData['region'];
                }
                if (isset($addressData['postal_code'])) {
                    $legacyPayload['PostalCode3'] = $addressData['postal_code'];
                } elseif (isset($addressData['zip_code'])) {
                    $legacyPayload['PostalCode3'] = $addressData['zip_code'];
                }
                if (isset($addressData['country_code'])) {
                    $legacyPayload['Country3'] = $addressData['country_code'];
                }
                if (isset($addressData['zip_four'])) {
                    $legacyPayload['ZipFour3'] = $addressData['zip_four'];
                }
                if (isset($addressData['field'])) {
                    $legacyPayload['Address3Type'] = $addressData['field'];
                }
            }
            // Legacy format only supports 3 addresses, ignore beyond that
        }
    }

    /**
     * Map custom fields using provided mapping or default naming
     *
     * @param  array  $payload  The API contact payload
     * @param  array<string, array{name: string, type: string}>  $customFieldMap  Map of custom field IDs to field config
     * @param  array  $legacyPayload  The legacy payload being built
     */
    protected static function mapCustomFields(array $payload, array $customFieldMap, array &$legacyPayload): void
    {
        if (! isset($payload['custom_fields']) || ! is_array($payload['custom_fields'])) {
            return;
        }

        foreach ($payload['custom_fields'] as $customField) {
            if (isset($customField['id']) && isset($customField['content'])) {
                $fieldId = (string) $customField['id'];

                $convertToDate = false;
                // Determine legacy field name from mapping or use default
                if (isset($customFieldMap[$fieldId])) {
                    $mapping = $customFieldMap[$fieldId];
                    $legacyFieldName = $mapping['name'];
                    $convertToDate = in_array($mapping['type'], ['DATE', 'DATE_TIME'], true);
                    // Type is available as $mapping['type'] if needed for future use
                } else {
                    $legacyFieldName = "_CustomField{$fieldId}";
                }

                $content = $convertToDate ? self::dateFromString($customField['content']) : $customField['content'];
                // Content might be an object/array, convert to string if needed
//                if (is_array($content) || is_object($content)) {
//                    $content = json_encode($content);
//                }

                $legacyPayload[$legacyFieldName] = $content;
            }
        }
    }

    protected static function mapTags(array $payload, array &$legacyPayload): void
    {
        if (isset($payload['tag_ids']) && is_array($payload['tag_ids'])) {
            $legacyPayload['Groups'] = $payload['tag_ids'];
        }
    }

    protected static function dateFromString(?string $dateString): ?\DateTimeImmutable
    {
        return new \DateTimeImmutable($dateString);
    }
}
