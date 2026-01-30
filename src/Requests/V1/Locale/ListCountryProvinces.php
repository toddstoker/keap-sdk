<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Locale;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Country Provinces (v1)
 *
 * Retrieves a list of provinces for a given country code.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ListCountryProvinces extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $countryCode
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/locales/countries/{$this->countryCode}/provinces";
    }
}
