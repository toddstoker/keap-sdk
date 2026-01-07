<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support\V2;

use Toddstoker\KeapSdk\Support\V2\FieldSelector\OrderPaymentFieldSelector;

/**
 * Query builder for Keap v2 Order Payments API
 *
 * Provides order payment-specific filter validation and helpers for the
 * List Order Payments endpoint via dynamic method calls.
 *
 * @method $this byInvoiceId(string $id) Filter by invoice ID
 * @method $this byPaymentId(string $id) Filter by payment ID
 * @method $this byAmount(string $amount) Filter by amount
 * @method $this byPayStatus(string $status) Filter by payment status
 * @method $this bySkipCommission(bool $skip) Filter by skip commission flag
 * @method $this orderByInvoiceId(string $direction = 'asc') Order by invoice ID
 * @method $this orderByPaymentId(string $direction = 'asc') Order by payment ID
 * @method $this orderByAmount(string $direction = 'asc') Order by amount
 * @method $this orderByPayTime(string $direction = 'asc') Order by payment time
 * @method $this orderByPayStatus(string $direction = 'asc') Order by payment status
 * @method $this orderBySkipCommission(string $direction = 'asc') Order by skip commission
 * @method $this orderByLastUpdatedTime(string $direction = 'asc') Order by last updated time
 */
class OrderPaymentQuery extends Query
{
    public function __construct()
    {
        $this->fieldSelector = new OrderPaymentFieldSelector;
    }

    /**
     * Allowed filter fields for order payments endpoint
     *
     * @var array<string>
     */
    protected array $allowedFilters = [
        'invoice_id',
        'payment_id',
        'amount',
        'pay_status',
        'skip_commission',
    ];

    /**
     * Allowed orderBy fields for order payments endpoint
     *
     * @var array<string>
     */
    protected array $allowedOrderBy = [
        'invoice_id',
        'payment_id',
        'amount',
        'pay_time',
        'pay_status',
        'skip_commission',
        'last_updated_time',
    ];
}
