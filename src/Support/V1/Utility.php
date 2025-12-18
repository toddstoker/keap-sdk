<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1;

use Toddstoker\KeapSdk\Support\ConvertsToLegacyPayload;

/**
 * Utility class for V1 API contact payload conversions
 */
class Utility
{
    use ConvertsToLegacyPayload;

    /**
     * Map V1-specific date fields
     *
     * V1 uses: birthday, anniversary, date_created, last_updated
     */
    protected static function mapDateFields(array $payload, array &$legacyPayload): void
    {
        if (isset($payload['birthday'])) {
            if($date = self::dateFromString($payload['birthday'])) {
                $legacyPayload['Birthday'] = $date;
            }
        }

        if (isset($payload['anniversary'])) {
            if($date = self::dateFromString($payload['anniversary'])) {
                $legacyPayload['Anniversary'] = $date;
            }
        }

        if (isset($payload['date_created'])) {
            if($date = self::dateFromString($payload['date_created'])) {
                $legacyPayload['DateCreated'] = $date;
            }
        }

        if (isset($payload['last_updated'])) {
            if($date = self::dateFromString($payload['last_updated'])) {
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
}
