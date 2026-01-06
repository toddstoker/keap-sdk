<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\ConvertsFromLegacyPayload;
use Toddstoker\KeapSdk\Support\ConvertsToLegacyPayload;

/**
 * Utility class for V2 API contact payload conversions
 */
class Utility
{
    use ConvertsFromLegacyPayload;
    use ConvertsToLegacyPayload;

    /**
     * Map V2-specific date fields
     *
     * V2 uses: birth_date, anniversary_date, create_time, update_time
     *
     * @param array{birth_date?: string|null, anniversary_date?: string|null, create_time?: string|null, update_time?: string|null,} $payload
     * @param array{Birthday?: string, Anniversary?: string, DateCreated?: string, LastUpdated?: string} $legacyPayload
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
     *
     * @param  array{owner_id?: string|int, leadsource_id?: string|int}  $payload
     * @param  array{OwnerID?: string|int, LeadSourceId?: string|int}  $legacyPayload
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

    /**
     * Map legacy date fields to V2 API format
     *
     * V2 target fields: birth_date, anniversary_date, create_time, update_time
     *
     * @param array{Birthday?: string, Anniversary?: string, DateCreated?: string, LastUpdated?: string} $legacyPayload
     * @param array{birth_date?: string|null, anniversary_date?: string|null, create_time?: string|null, update_time?: string|null} $apiPayload
     */
    protected static function mapLegacyDateFields(array $legacyPayload, array &$apiPayload): void
    {
        // Birthday: DateTimeImmutable or string → date string (YYYY-MM-DD)
        if (isset($legacyPayload['Birthday']) && $legacyPayload['Birthday'] !== '') {
            $apiPayload['birth_date'] = self::dateToString($legacyPayload['Birthday'], 'Y-m-d');
        }

        // Anniversary: DateTimeImmutable or string → date string (YYYY-MM-DD)
        if (isset($legacyPayload['Anniversary']) && $legacyPayload['Anniversary'] !== '') {
            $apiPayload['anniversary_date'] = self::dateToString($legacyPayload['Anniversary'], 'Y-m-d');
        }

        // DateCreated: DateTimeImmutable or string → RFC 3339 timestamp
        if (isset($legacyPayload['DateCreated']) && $legacyPayload['DateCreated'] !== '') {
            $apiPayload['create_time'] = self::dateToString($legacyPayload['DateCreated'], 'c');
        }

        // LastUpdated: DateTimeImmutable or string → RFC 3339 timestamp
        if (isset($legacyPayload['LastUpdated']) && $legacyPayload['LastUpdated'] !== '') {
            $apiPayload['update_time'] = self::dateToString($legacyPayload['LastUpdated'], 'c');
        }
    }

    /**
     * Map legacy ID fields to V2 API format
     *
     * V2 uses: owner_id, leadsource_id (no underscore)
     *
     * @param array{OwnerID?: string, LeadSourceId?: string} $legacyPayload
     * @param array{owner_id?: string, leadsource_id?: string} $apiPayload
     */
    protected static function mapLegacyIdFields(array $legacyPayload, array &$apiPayload): void
    {
        if (isset($legacyPayload['OwnerID']) && $legacyPayload['OwnerID'] !== '') {
            $apiPayload['owner_id'] = $legacyPayload['OwnerID'];
        }

        if (isset($legacyPayload['LeadSourceId']) && $legacyPayload['LeadSourceId'] !== '') {
            $apiPayload['leadsource_id'] = $legacyPayload['LeadSourceId'];
        }
    }

    /**
     * Map legacy company fields to V2 API format
     *
     * V2 uses nested structure: company: {company_name, id}
     *
     * @param  array{Company?: string, CompanyID?: string}  $legacyPayload
     * @param  array{company?: array{company_name?: string, id?: string}}  $apiPayload
     */
    protected static function mapLegacyCompanyFields(array $legacyPayload, array &$apiPayload): void
    {
        $company = [];

        if (isset($legacyPayload['Company']) && $legacyPayload['Company'] !== '') {
            $company['company_name'] = $legacyPayload['Company'];
        }

        if (isset($legacyPayload['CompanyID']) && $legacyPayload['CompanyID'] !== '') {
            $company['id'] = (string) $legacyPayload['CompanyID'];
        }

        if (! empty($company)) {
            $apiPayload['company'] = $company;
        }
    }
}
