<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Locale;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Dropdown Default Options (v1)
 *
 * Retrieves default dropdown options for contact types, fax types, phone types,
 * suffix types, and title types.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class ListDropdownDefaultOptions extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v1/locales/defaultOptions';
    }
}
