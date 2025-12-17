<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\ConvertsToLegacyPayload;

/**
 * Utility class for V2 API contact payload conversions
 */
class Utility
{
    use ConvertsToLegacyPayload;

    /**
     * Map V2-specific date fields
     *
     * V2 uses: birth_date, anniversary_date, create_time, update_time
     */
    protected static function mapDateFields(array $payload, array &$legacyPayload): void
    {
        if (isset($payload['birth_date'])) {
            $legacyPayload['Birthday'] = $payload['birth_date'];
        }

        if (isset($payload['anniversary_date'])) {
            $legacyPayload['Anniversary'] = $payload['anniversary_date'];
        }

        if (isset($payload['create_time'])) {
            $legacyPayload['DateCreated'] = $payload['create_time'];
        }

        if (isset($payload['update_time'])) {
            $legacyPayload['LastUpdated'] = $payload['update_time'];
        }
    }

    /**
     * Map V2-specific ID fields
     *
     * V2 uses: owner_id, leadsource_id (no underscore)
     */
    protected static function mapIdFields(array $payload, array &$legacyPayload): void
    {
        if (isset($payload['owner_id'])) {
            $legacyPayload['OwnerID'] = $payload['owner_id'];
        }

        if (isset($payload['leadsource_id'])) {
            $legacyPayload['LeadSourceId'] = $payload['leadsource_id'];
        }
    }
}
