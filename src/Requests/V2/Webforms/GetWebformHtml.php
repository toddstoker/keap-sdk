<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Webforms;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Webform HTML (v2)
 *
 * Retrieves the HTML content for a specific webform.
 *
 * Note: This endpoint returns HTML content (text/html), not JSON.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class GetWebformHtml extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $webformId  The webform ID
     */
    public function __construct(
        protected readonly string $webformId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/webforms/{$this->webformId}:data";
    }

    public function defaultHeaders(): array
    {
        return [
            'Accept' => 'text/html',
        ];
    }
}
