<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Closure;
use Generator;

/**
 * Paginator for Keap v2 API list endpoints
 *
 * Handles cursor-based pagination automatically, fetching pages
 * as needed and yielding individual items.
 *
 * @example
 * ```php
 * $paginator = new Paginator(
 *     fn($query) => $keap->contacts()->list($query),
 *     ContactQuery::make()->pageSize(100)
 * );
 *
 * // Iterate through all contacts across all pages
 * foreach ($paginator->items() as $contact) {
 *     echo $contact->email;
 * }
 *
 * // Or get pages manually
 * $page1 = $paginator->getPage();
 * $page2 = $paginator->nextPage();
 * ```
 */
class Paginator
{
    /**
     * The query builder instance
     */
    protected Query $query;

    /**
     * The fetch callback that executes the API request
     */
    protected Closure $fetchCallback;

    /**
     * The current page response data
     *
     * @var array<string, mixed>|null
     */
    protected ?array $currentPage = null;

    /**
     * Whether we've fetched the first page
     */
    protected bool $initialized = false;

    /**
     * Create a new Paginator instance
     *
     * @param  Closure  $fetchCallback  Callback that accepts a Query and returns the API response
     * @param  Query  $query  The query builder instance
     */
    public function __construct(Closure $fetchCallback, Query $query)
    {
        $this->fetchCallback = $fetchCallback;
        $this->query = $query;
    }

    /**
     * Create a new Paginator instance
     *
     * @param  Closure  $fetchCallback  Callback that accepts a Query and returns the API response
     * @param  Query  $query  The query builder instance
     */
    public static function make(Closure $fetchCallback, Query $query): static
    {
        return new static($fetchCallback, $query);
    }

    /**
     * Get the current page of results
     *
     * If no page has been fetched yet, fetches the first page.
     *
     * @return array<string, mixed> The page response data
     */
    public function getPage(): array
    {
        if (! $this->initialized) {
            $this->currentPage = ($this->fetchCallback)($this->query);
            $this->initialized = true;
        }

        return $this->currentPage ?? [];
    }

    /**
     * Fetch the next page of results
     *
     * @return array<string, mixed>|null The next page data, or null if no more pages
     */
    public function nextPage(): ?array
    {
        $currentPage = $this->getPage();

        // Check if there's a next page token
        $nextToken = $currentPage['next_page_token'] ?? null;

        // No more pages if token is null or empty string
        if ($nextToken === null || $nextToken === '') {
            return null;
        }

        // Update query with next page token and fetch
        $this->query = $this->query->pageToken($nextToken);
        $this->currentPage = ($this->fetchCallback)($this->query);

        return $this->currentPage;
    }

    /**
     * Check if there are more pages available
     */
    public function hasMorePages(): bool
    {
        $currentPage = $this->getPage();

        $nextToken = $currentPage['next_page_token'] ?? null;

        // Has more pages if token exists and is not empty
        return $nextToken !== null && $nextToken !== '';
    }

    /**
     * Iterate through all items across all pages
     *
     * This generator will automatically fetch subsequent pages as needed.
     *
     * @param  string  $key  The key in the response containing the items array (e.g., 'contacts', 'companies')
     */
    public function items(string $key): Generator
    {
        $page = $this->getPage();

        while ($page) {
            // Yield items from current page
            $items = $page[$key] ?? [];
            foreach ($items as $item) {
                yield $item;
            }

            // Fetch next page if available
            if (! $this->hasMorePages()) {
                break;
            }

            $page = $this->nextPage();
        }
    }

    /**
     * Iterate through all pages (not individual items)
     */
    public function pages(): Generator
    {
        $page = $this->getPage();

        while ($page) {
            yield $page;

            if (! $this->hasMorePages()) {
                break;
            }

            $page = $this->nextPage();
        }
    }

    /**
     * Get all items from all pages as an array
     *
     * WARNING: This will fetch all pages and load all results into memory.
     * Use with caution for large result sets.
     *
     * @param  string  $key  The key in the response containing the items array
     * @return array<mixed>
     */
    public function all(string $key): array
    {
        return iterator_to_array($this->items($key), false);
    }
}
