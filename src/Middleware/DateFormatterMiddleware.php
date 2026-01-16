<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Middleware;

use DateTimeInterface;
use Saloon\Http\PendingRequest;
use Toddstoker\KeapSdk\Support\DateFormatter;

/**
 * Middleware to automatically format DateTimeInterface values in requests
 *
 * This middleware intercepts outgoing requests and converts any DateTimeInterface
 * values found in query parameters or request body to Keap-compatible string format.
 *
 * This allows developers to pass DateTimeInterface objects directly when building
 * queries or request data, without worrying about manual string conversion.
 */
readonly class DateFormatterMiddleware
{

    public function __construct() {}

    public function __invoke(PendingRequest $pendingRequest): void
    {
        $this->transformQueryParameters($pendingRequest);
        $this->transformBody($pendingRequest);
    }

    /**
     * Transform DateTimeInterface values in query parameters
     */
    protected function transformQueryParameters(PendingRequest $pendingRequest): void
    {
        $query = $pendingRequest->query()->all();

        if (empty($query)) {
            return;
        }

        $transformed = $this->transformDates($query);

        // Clear and re-add to ensure clean state
        foreach ($query as $key => $value) {
            $pendingRequest->query()->remove($key);
        }

        $pendingRequest->query()->merge($transformed);
    }

    /**
     * Transform DateTimeInterface values in request body
     */
    protected function transformBody(PendingRequest $pendingRequest): void
    {
        $body = $pendingRequest->body();

        if ($body === null) {
            return;
        }

        // Handle array-based bodies (JSON, form data)
        $data = $body->all();

        if (! is_array($data) || empty($data)) {
            return;
        }

        $transformed = $this->transformDates($data);

        // Replace the entire body with the transformed data
        $body->set($transformed);
    }

    /**
     * Recursively transform DateTimeInterface values in an array
     *
     * @param  array<string, mixed>  $data  The data array to transform
     * @return array<string, mixed> The transformed array
     */
    protected function transformDates(array $data): array
    {
        foreach ($data as $key => $value) {
            if ($value instanceof DateTimeInterface) {
                $format = in_array($key, ['anniversary_date', 'birth_date'], true)
                    ? DateFormatter::DATE // Standard date fields must be DATE only
                    : DateFormatter::DATETIME; // custom fields, whether date or datetime, accept full DATETIME strings
                $data[$key] = DateFormatter::format($value, $format);
            } elseif (is_array($value)) {
                $data[$key] = $this->transformDates($value);
            }
        }

        return $data;
    }
}
