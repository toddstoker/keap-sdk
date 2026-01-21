<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Webforms\GetWebformHtml;
use Toddstoker\KeapSdk\Requests\V2\Webforms\ListWebforms;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\Paginator;
use Toddstoker\KeapSdk\Support\V2\WebformQuery;

/**
 * Webforms Resource (v2)
 *
 * Provides methods for retrieving webforms and their HTML content.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class WebformsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List webforms with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  WebformQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{
     *     webforms: array<int, array{
     *         id: string,
     *         name: string,
     *         webform_type: string,
     *         xid?: string,
     *         custom_slug?: string,
     *         duplicate_check_option?: string,
     *         exit_option?: string,
     *         funnel_id?: string,
     *         pretty_webform_url?: string,
     *         thank_you_page_url?: string,
     *         webform_url?: string,
     *         create_time?: string,
     *         update_time?: string
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?WebformQuery $query = null): array
    {
        $query = $query ?? WebformQuery::make();

        $response = $this->connector->send(new ListWebforms($query));

        return $response->json();
    }

    /**
     * Create a paginator for iterating through the list webforms endpoint.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  WebformQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListPaginator(?WebformQuery $query = null): Paginator
    {
        $query = $query ?? WebformQuery::make();

        return new Paginator(
            fn (WebformQuery $q) => $this->list($q),
            $query,
            'webforms'
        );
    }

    /**
     * Get webform HTML
     *
     * Retrieves the HTML content for a specific webform.
     *
     * @param  string  $webformId  The webform ID
     * @return string The HTML content of the webform
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getHtml(string $webformId): string
    {
        $response = $this->connector->send(new GetWebformHtml($webformId));

        return $response->body();
    }
}
