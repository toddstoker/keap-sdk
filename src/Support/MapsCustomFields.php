<?php

namespace Toddstoker\KeapSdk\Support;

use Toddstoker\KeapSdk\Keap;

trait MapsCustomFields
{
    /**
     * Retrieve a map of custom field IDs to their names and types.
     * Can use an optional caching resolver to cache the result:
     * ```
     * Utility::getCustomFieldMap($sdk, function (callable $resolver) {
     *     return Cache::remember('field_map', 3600, $resolver);
     * });
     * ```
     *
     *
     * @param  callable|null  $cacheResolver  Optional caching resolver function
     * @return array<int, array{name: string, type: string}>
     */
    public static function getCustomFieldMap(Keap $sdk, ?callable $cacheResolver = null): array
    {
        $buildMap = static function () use ($sdk): array {
            $customFields = $sdk->contacts(2)->getModel()['custom_fields'] ?? [];

            $customFieldMap = [];
            foreach ($customFields as $customField) {
                $customFieldMap[$customField['id']] = [
                    'name' => '_'.$customField['field_name'],
                    'type' => $customField['field_type'],
                ];
            }

            return $customFieldMap;
        };

        if ($cacheResolver !== null) {
            return $cacheResolver($buildMap);
        }

        return $buildMap();
    }
}
