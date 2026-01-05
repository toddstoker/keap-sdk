<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1;

use Toddstoker\KeapSdk\Support\ConvertsFromLegacyPayload;
use Toddstoker\KeapSdk\Support\ConvertsToLegacyPayload;

/**
 * Utility class for V1 API contact payload conversions
 */
class Utility
{
    use ConvertsFromLegacyPayload;
    use ConvertsToLegacyPayload;

    /**
     * Map V1-specific date fields
     *
     * V1 uses: birthday, anniversary, date_created, last_updated
     */
    protected static function mapDateFields(array $payload, array &$legacyPayload): void
    {
        if (isset($payload['birthday'])) {
            if ($date = self::dateFromString($payload['birthday'])) {
                $legacyPayload['Birthday'] = $date;
            }
        }

        if (isset($payload['anniversary'])) {
            if ($date = self::dateFromString($payload['anniversary'])) {
                $legacyPayload['Anniversary'] = $date;
            }
        }

        if (isset($payload['date_created'])) {
            if ($date = self::dateFromString($payload['date_created'])) {
                $legacyPayload['DateCreated'] = $date;
            }
        }

        if (isset($payload['last_updated'])) {
            if ($date = self::dateFromString($payload['last_updated'])) {
                $legacyPayload['LastUpdated'] = $date;
            }
        }
    }

    /**
     * Map V1-specific ID fields
     *
     * V1 uses: owner_id, lead_source_id (with underscore)
     */
    protected static function mapIdFields(array $payload, array &$legacyPayload): void
    {
        if (isset($payload['owner_id'])) {
            $legacyPayload['OwnerID'] = $payload['owner_id'];
        }

        if (isset($payload['lead_source_id'])) {
            $legacyPayload['LeadSourceId'] = $payload['lead_source_id'];
        }
    }

    /**
     * Map legacy date fields to V1 API format
     *
     * V1 target fields: birthday, anniversary, date_created, last_updated
     */
    protected static function mapLegacyDateFields(array $legacyPayload, array &$apiPayload): void
    {
        // Birthday: DateTimeImmutable or string → ISO 8601 datetime string
        if (isset($legacyPayload['Birthday']) && $legacyPayload['Birthday'] !== '') {
            $apiPayload['birthday'] = self::dateToString($legacyPayload['Birthday'], 'Y-m-d\TH:i:s\Z');
        }

        // Anniversary: DateTimeImmutable or string → date string (YYYY-MM-DD)
        if (isset($legacyPayload['Anniversary']) && $legacyPayload['Anniversary'] !== '') {
            $apiPayload['anniversary'] = self::dateToString($legacyPayload['Anniversary'], 'Y-m-d');
        }

        // DateCreated: DateTimeImmutable or string → ISO 8601 datetime string
        if (isset($legacyPayload['DateCreated']) && $legacyPayload['DateCreated'] !== '') {
            $apiPayload['date_created'] = self::dateToString($legacyPayload['DateCreated'], 'Y-m-d\TH:i:s\Z');
        }

        // LastUpdated: DateTimeImmutable or string → ISO 8601 datetime string
        if (isset($legacyPayload['LastUpdated']) && $legacyPayload['LastUpdated'] !== '') {
            $apiPayload['last_updated'] = self::dateToString($legacyPayload['LastUpdated'], 'Y-m-d\TH:i:s\Z');
        }
    }

    /**
     * Map legacy ID fields to V1 API format
     *
     * V1 uses: owner_id, lead_source_id (with underscore)
     */
    protected static function mapLegacyIdFields(array $legacyPayload, array &$apiPayload): void
    {
        if (isset($legacyPayload['OwnerID']) && $legacyPayload['OwnerID'] !== '') {
            $apiPayload['owner_id'] = $legacyPayload['OwnerID'];
        }

        if (isset($legacyPayload['LeadSourceId']) && $legacyPayload['LeadSourceId'] !== '') {
            $apiPayload['lead_source_id'] = $legacyPayload['LeadSourceId'];
        }
    }

    /**
     * Map legacy company fields to V1 API format
     *
     * V1 uses flat structure: company_name field
     */
    protected static function mapLegacyCompanyFields(array $legacyPayload, array &$apiPayload): void
    {
        if (isset($legacyPayload['Company']) && $legacyPayload['Company'] !== '') {
            $apiPayload['company_name'] = $legacyPayload['Company'];
        }

        // Note: V1 API doesn't appear to have a company ID field based on the existing conversion
        // If needed in the future, it can be added here
    }
}
