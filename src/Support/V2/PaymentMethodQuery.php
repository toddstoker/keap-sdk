<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\PaymentMethodFieldSelector;

/**
 * Query builder for Keap v2 Payment Methods API
 *
 * Provides payment method-specific filter validation and helpers for the
 * List Payment Methods endpoint via dynamic method calls.
 *
 * @method $this byMerchantAccountId(string $id) Filter by merchant account ID
 * @method $this orderByCreatedTime(string $direction = 'asc') Order by created time
 */
class PaymentMethodQuery extends Query
{

    public function __construct()
    {
        $this->fieldSelector = new PaymentMethodFieldSelector();
    }

    /**
     * Allowed filter fields for payment methods endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'merchant_account_id',
    ];

    /**
     * Allowed orderBy fields for payment methods endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'created_time',
    ];
}
