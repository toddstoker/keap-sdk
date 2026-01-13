<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Orders\GetOrder;
use Toddstoker\KeapSdk\Requests\V2\Orders\ListOrderPayments;
use Toddstoker\KeapSdk\Requests\V2\Orders\ListOrders;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\FieldSelector\OrderFieldSelector;
use Toddstoker\KeapSdk\Support\V2\OrderPaymentQuery;
use Toddstoker\KeapSdk\Support\V2\OrderQuery;
use Toddstoker\KeapSdk\Support\V2\Paginator;

/**
 * Orders Resource (v2)
 *
 * Provides methods for interacting with the Keap Orders API v2.
 *
 * Orders represent e-commerce transactions in Keap, including products,
 * payments, and shipping information.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class OrdersResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List orders with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  OrderQuery|null  $query  Query builder with filters and pagination options
     * @return array{
     *     orders: array<int, array{
     *         id: string,
     *         contact?: array{
     *             id: string,
     *             given_name?: string,
     *             family_name?: string,
     *             email?: string
     *         },
     *         order_time?: string,
     *         creation_time?: string,
     *         modification_time?: string,
     *         status?: string,
     *         order_type?: string,
     *         source_type?: string,
     *         title?: string,
     *         notes?: string,
     *         terms?: string,
     *         invoice_number?: string,
     *         total?: array{
     *             amount: int,
     *             currency_code: string,
     *             formatted_amount: string
     *         },
     *         total_due?: array{
     *             amount: int,
     *             currency_code: string,
     *             formatted_amount: string
     *         },
     *         total_paid?: array{
     *             amount: int,
     *             currency_code: string,
     *             formatted_amount: string
     *         },
     *         refund_total?: array{
     *             amount: int,
     *             currency_code: string,
     *             formatted_amount: string
     *         },
     *         order_items?: array<int, array{
     *             id: string,
     *             name?: string,
     *             description?: string,
     *             item_type?: string,
     *             quantity?: int,
     *             price_per_unit?: array{
     *                 amount: int,
     *                 currency_code: string,
     *                 formatted_amount: string
     *             },
     *             cost_per_unit?: array{
     *                 amount: int,
     *                 currency_code: string,
     *                 formatted_amount: string
     *             },
     *             discount?: array{
     *                 amount: int,
     *                 currency_code: string,
     *                 formatted_amount: string
     *             },
     *             product?: array{
     *                 id: string,
     *                 name?: string,
     *                 description?: string,
     *                 sku?: string,
     *                 shippable?: bool,
     *                 taxable?: bool
     *             },
     *             notes?: string
     *         }>,
     *         shipping_information?: array{
     *             id?: string,
     *             given_name?: string,
     *             family_name?: string,
     *             company?: string,
     *             phone_number?: string,
     *             invoice_to_company?: bool,
     *             address?: array{
     *                 line1?: string,
     *                 line2?: string,
     *                 locality?: string,
     *                 region?: string,
     *                 region_code?: string,
     *                 postal_code?: string,
     *                 country?: string,
     *                 country_code?: string,
     *                 field?: string
     *             }
     *         },
     *         payment_plan?: array{
     *             auto_charge?: bool,
     *             days_between_payments: int,
     *             days_between_retries?: int,
     *             initial_payment_amount?: array{
     *                 amount: int,
     *                 currency_code: string,
     *                 formatted_amount: string
     *             },
     *             initial_payment_date?: string,
     *             initial_payment_percent?: float,
     *             max_charge_attempts?: int,
     *             number_of_payments: int,
     *             payment_method_id?: string,
     *             plan_start_date: string
     *         },
     *         lead_affiliate_id?: string,
     *         sales_affiliate_id?: string,
     *         allow_payment?: bool,
     *         allow_paypal?: bool,
     *         files?: array<int, array{file_id: string}>
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?OrderQuery $query = null): array
    {
        $query = $query ?? OrderQuery::make();

        $response = $this->connector->send(new ListOrders($query));
        $data = $response->json();

        return [
            'orders' => $data['orders'] ?? [],
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through the list orders endpoint
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  OrderQuery|null  $query  Query builder with filters and pagination options
     */
    public function newListPaginator(?OrderQuery $query = null): Paginator
    {
        $query = $query ?? OrderQuery::make();

        return new Paginator(
            fn (OrderQuery $q) => $this->list($q),
            $query,
            'orders'
        );
    }

    /**
     * Get a specific order by ID
     *
     * Supports optional field selection. Pass an array of field names,
     * an OrderFieldSelector instance, or null to get all available fields.
     *
     * Note: The Orders API does not currently support field selection,
     * but the parameter exists for consistency and future compatibility.
     *
     * @param  string|int  $orderId  The order ID
     * @param  OrderFieldSelector|array<string>|null  $fields  Fields to include in response (not currently supported)
     * @return array{
     *     id: string,
     *     contact?: array{
     *         id: string,
     *         given_name?: string,
     *         family_name?: string,
     *         email?: string
     *     },
     *     order_time?: string,
     *     creation_time?: string,
     *     modification_time?: string,
     *     status?: string,
     *     order_type?: string,
     *     source_type?: string,
     *     title?: string,
     *     notes?: string,
     *     terms?: string,
     *     invoice_number?: string,
     *     total?: array{
     *         amount: int,
     *         currency_code: string,
     *         formatted_amount: string
     *     },
     *     total_due?: array{
     *         amount: int,
     *         currency_code: string,
     *         formatted_amount: string
     *     },
     *     total_paid?: array{
     *         amount: int,
     *         currency_code: string,
     *         formatted_amount: string
     *     },
     *     refund_total?: array{
     *         amount: int,
     *         currency_code: string,
     *         formatted_amount: string
     *     },
     *     order_items?: array<int, array{
     *         id: string,
     *         name?: string,
     *         description?: string,
     *         item_type?: string,
     *         quantity?: int,
     *         price_per_unit?: array{
     *             amount: int,
     *             currency_code: string,
     *             formatted_amount: string
     *         },
     *         cost_per_unit?: array{
     *             amount: int,
     *             currency_code: string,
     *             formatted_amount: string
     *         },
     *         discount?: array{
     *             amount: int,
     *             currency_code: string,
     *             formatted_amount: string
     *         },
     *         product?: array{
     *             id: string,
     *             name?: string,
     *             description?: string,
     *             sku?: string,
     *             shippable?: bool,
     *             taxable?: bool
     *         },
     *         notes?: string
     *     }>,
     *     shipping_information?: array{
     *         id?: string,
     *         given_name?: string,
     *         family_name?: string,
     *         company?: string,
     *         phone_number?: string,
     *         invoice_to_company?: bool,
     *         address?: array{
     *             line1?: string,
     *             line2?: string,
     *             locality?: string,
     *             region?: string,
     *             region_code?: string,
     *             postal_code?: string,
     *             country?: string,
     *             country_code?: string,
     *             field?: string
     *         }
     *     },
     *     payment_plan?: array{
     *         auto_charge?: bool,
     *         days_between_payments: int,
     *         days_between_retries?: int,
     *         initial_payment_amount?: array{
     *             amount: int,
     *             currency_code: string,
     *             formatted_amount: string
     *         },
     *         initial_payment_date?: string,
     *         initial_payment_percent?: float,
     *         max_charge_attempts?: int,
     *         number_of_payments: int,
     *         payment_method_id?: string,
     *         plan_start_date: string
     *     },
     *     lead_affiliate_id?: string,
     *     sales_affiliate_id?: string,
     *     allow_payment?: bool,
     *     allow_paypal?: bool,
     *     files?: array<int, array{file_id: string}>,
     *     ...
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(string|int $orderId, OrderFieldSelector|array|null $fields = null): array
    {
        // Convert array to OrderFieldSelector if needed
        if (is_array($fields)) {
            $fieldSelector = OrderFieldSelector::make()->fields($fields);
        } else {
            $fieldSelector = $fields;
        }

        $response = $this->connector->send(new GetOrder($orderId, $fieldSelector));

        return $response->json();
    }

    /**
     * List payments for a specific order
     *
     * Retrieves a list of payments made against a given order, including
     * historical or external payments of cash or credit card.
     *
     * Returns a single page of results. Use newListPaymentsPaginator() to
     * automatically iterate through all pages.
     *
     * @param  string|int  $orderId  Order ID
     * @param  OrderPaymentQuery|null  $query  Query builder with filters and pagination options
     * @return array{
     *     invoice_order_payments: array<int, array{
     *         id: string,
     *         invoice_id?: string,
     *         payment_id?: string,
     *         amount?: float,
     *         pay_time?: string,
     *         pay_status?: string,
     *         skip_commission?: bool,
     *         note?: string,
     *         last_updated_time?: string,
     *         refund_invoice_payment_id?: string
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function listPayments(string|int $orderId, ?OrderPaymentQuery $query = null): array
    {
        $query = $query ?? OrderPaymentQuery::make();

        $response = $this->connector->send(new ListOrderPayments($orderId, $query));
        $data = $response->json();

        return [
            'invoice_order_payments' => $data['invoice_order_payments'] ?? [],
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through order payments
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  string|int  $orderId  Order ID
     * @param  OrderPaymentQuery|null  $query  Query builder with filters and pagination options
     */
    public function newListPaymentsPaginator(string|int $orderId, ?OrderPaymentQuery $query = null): Paginator
    {
        $query = $query ?? OrderPaymentQuery::make();

        return new Paginator(
            fn (OrderPaymentQuery $q) => $this->listPayments($orderId, $q),
            $query,
            'invoice_order_payments'
        );
    }
}
