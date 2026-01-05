<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V1;

use Closure;
use Generator;

/**
 * Paginator for Keap v1 API list endpoints
 *
 * Handles offset-based pagination automatically, fetching pages
 * as needed and yielding individual items.
 *
 * @example
 * ```php
 * $paginator = new Paginator(
 *     fn($query) => $keap->contacts()->list($query),
 *     ContactQuery::make()->limit(100)
 * );
 *
 * // Iterate through all contacts across all pages
 * foreach ($paginator->items() as $contact) {
 *     echo $contact['email'];
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
     * Current offset position
     */
    protected int $currentOffset = 0;

    /**
     * Create a new Paginator instance
     *
     * @param  Closure  $fetchCallback  Callback that accepts a Query and returns the API response
     * @param  Query  $query  The query builder instance
     * @param  string  $itemKey  The key in the response containing the items array (e.g., 'contacts', 'tags')
     */
    public function __construct(
        protected Closure $fetchCallback,
        protected Query $query,
        protected string $itemKey
    ) {
        $this->currentOffset = $query->getOffset() ?? 0;
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

        // Get the number of items in the current page
        $items = $currentPage[$this->itemKey] ?? [];
        $count = count($items);

        // If we got fewer items than the limit, we're on the last page
        $limit = $this->query->getLimit() ?? 100;
        if ($count < $limit) {
            return null;
        }

        // Update offset and fetch next page
        $this->currentOffset += $count;
        $this->query = $this->query->offset($this->currentOffset);
        $this->currentPage = ($this->fetchCallback)($this->query);

        // Check if the new page has any items
        $newItems = $this->currentPage[$this->itemKey] ?? [];
        if (empty($newItems)) {
            return null;
        }

        return $this->currentPage;
    }

    /**
     * Check if there are more pages available
     */
    public function hasMorePages(): bool
    {
        $currentPage = $this->getPage();

        $items = $currentPage[$this->itemKey] ?? [];
        $count = count($items);
        $limit = $this->query->getLimit() ?? 100;

        // If we got fewer items than the limit, no more pages
        return $count >= $limit;
    }

    /**
     * Iterate through all items across all pages
     *
     * This generator will automatically fetch subsequent pages as needed.
     */
    public function items(): Generator
    {
        $page = $this->getPage();

        while ($page) {
            // Yield items from current page
            $items = $page[$this->itemKey] ?? [];
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
     * @return array<mixed>
     */
    public function all(): array
    {
        return iterator_to_array($this->items(), false);
    }
}
