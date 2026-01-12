<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Settings\GetApplicationConfiguration;
use Toddstoker\KeapSdk\Resources\Resource;

/**
 * Settings Resource (v2)
 *
 * Provides methods for retrieving application settings and configuration.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class SettingsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * Get application configuration values
     *
     * By default, only application data is returned. You can specify additional
     * fields to include in the response using the $fields parameter.
     *
     * @param  array<string>|null  $fields  Optional fields to include (AFFILIATE, APPOINTMENT, CONTACT, ECOMMERCE, EMAIL, FORMS, FULFILLMENT, INVOICE, NOTE, OPPORTUNITY, TASK, TEMPLATE)
     * @return array{
     *     application?: array{
     *         address?: string,
     *         business_goals?: array<string>,
     *         business_primary_color?: string,
     *         business_secondary_color?: string,
     *         business_type?: string,
     *         country?: string,
     *         currency?: string,
     *         currency_format?: string,
     *         language?: string,
     *         logo_url?: string,
     *         name?: string,
     *         phone?: string,
     *         team_size?: string,
     *         time_zone?: string,
     *         website?: string
     *     },
     *     affiliate?: array<string, mixed>,
     *     appointment?: array<string, mixed>,
     *     contact?: array<string, mixed>,
     *     ecommerce?: array<string, mixed>,
     *     email?: array<string, mixed>,
     *     forms?: array<string, mixed>,
     *     fulfillment?: array<string, mixed>,
     *     invoice?: array<string, mixed>,
     *     note?: array<string, mixed>,
     *     opportunity?: array<string, mixed>,
     *     task?: array<string, mixed>,
     *     template?: array<string, mixed>
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function getApplicationConfiguration(?array $fields = null): array
    {
        $response = $this->connector->send(new GetApplicationConfiguration($fields));

        return $response->json();
    }
}
