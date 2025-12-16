<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V1\Opportunities;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Opportunity Model (v1)
 *
 * Retrieves the custom fields for the Opportunity object.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
class GetOpportunityModel extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/opportunities/model';
    }
}
