<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V1;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Locale\ListCountries;
use Toddstoker\KeapSdk\Requests\V1\Locale\ListCountryProvinces;
use Toddstoker\KeapSdk\Requests\V1\Locale\ListDropdownDefaultOptions;
use Toddstoker\KeapSdk\Resources\Resource;

/**
 * Locale Resource (v1)
 *
 * Provides methods for retrieving locale data including countries, provinces, and dropdown default options.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
readonly class LocaleResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List countries
     *
     * Retrieves a list of all countries.
     *
     * @return array{countries: array<string, string>}
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listCountries(): array
    {
        $response = $this->connector->send(new ListCountries);

        return $response->json();
    }

    /**
     * List country provinces
     *
     * Retrieves a list of provinces for a specific country.
     *
     * @param  string  $countryCode  The ISO country code
     * @return array{provinces: array<string, string>}
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listCountryProvinces(string $countryCode): array
    {
        $response = $this->connector->send(new ListCountryProvinces($countryCode));

        return $response->json();
    }

    /**
     * List dropdown default options
     *
     * Retrieves default options for various dropdown fields.
     *
     * @return array{
     *     contact_types: array<int, string>,
     *     fax_types: array<int, string>,
     *     phone_types: array<int, string>,
     *     suffix_types: array<int, string>,
     *     title_types: array<int, string>
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listDropdownDefaultOptions(): array
    {
        $response = $this->connector->send(new ListDropdownDefaultOptions);

        return $response->json();
    }
}
