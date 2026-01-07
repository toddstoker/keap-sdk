<?php

namespace Toddstoker\KeapSdk;

class KeapResponse extends \Saloon\Http\Response
{
    /**
     * Get the rate limit information from the response headers.
     *
     * @return array<string, array<string, int|\DateTimeImmutable>>
     */
    public function getRateLimits(): array
    {
        return [
            'daily' => [
                'limit' => (int) $this->header('x-keap-product-quota-limit'),
                'used' => (int) $this->header('x-keap-product-quota-used'),
                'available' => (int) $this->header('x-keap-product-quota-available'),
                'resets' => \DateTimeImmutable::createFromTimestamp((int) $this->header('x-keap-product-quota-expiry-time') / 1000),
            ],
            'minute' => [
                'limit' => (int) $this->header('x-keap-product-throttle-limit'),
                'used' => (int) $this->header('x-keap-product-throttle-used'),
                'available' => (int) $this->header('x-keap-product-throttle-available'),
            ],
            'tenant' => [
                'limit' => (int) $this->header('x-keap-tenant-throttle-limit'),
                'used' => (int) $this->header('x-keap-tenant-throttle-used'),
                'available' => (int) $this->header('x-keap-tenant-throttle-available'),
            ],
        ];
    }
}
