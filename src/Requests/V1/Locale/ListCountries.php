<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Locale;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Countries (v1)
 *
 * Retrieves a list of all countries with their codes.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ListCountries extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v1/locales/countries';
    }
}
