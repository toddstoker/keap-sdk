<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\PaymentMethods\DeactivatePaymentMethod;
use Toddstoker\KeapSdk\Requests\V2\PaymentMethods\DeletePaymentMethod;
use Toddstoker\KeapSdk\Requests\V2\PaymentMethods\ListPaymentMethods;
use Toddstoker\KeapSdk\Requests\V2\PaymentMethods\NewSessionKey;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\Paginator;
use Toddstoker\KeapSdk\Support\V2\PaymentMethodQuery;

/**
 * Payment Methods Resource (v2)
 *
 * Provides methods for interacting with the Keap Payment Methods API v2.
 *
 * Payment methods represent stored payment information (typically credit cards)
 * associated with contacts in Keap.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class PaymentMethodsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List payment methods for a contact
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  int|string  $contactId  Contact ID or '-' to filter across all contacts
     * @param  PaymentMethodQuery|null  $query  Query builder with filters and pagination options
     * @return array{
     *     records: array<int, array{
     *         payment_method_id: string,
     *         contact_id: string,
     *         payment_method_type: string,
     *         merchant_account_id: string,
     *         merchant_account_type: string,
     *         created_time: string,
     *         status?: string,
     *         card_info?: array{
     *             brand: string,
     *             card_type: string,
     *             expiration_month: string,
     *             expiration_year: string,
     *             last_four: string
     *         }
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function list(int|string $contactId, ?PaymentMethodQuery $query = null): array
    {
        $query = $query ?? PaymentMethodQuery::make();

        $response = $this->connector->send(new ListPaymentMethods($contactId, $query));
        $data = $response->json();

        return [
            'records' => $data['records'] ?? [],
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through payment methods
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  int|string  $contactId  Contact ID or '-' to filter across all contacts
     * @param  PaymentMethodQuery|null  $query  Query builder with filters and pagination options
     */
    public function newListPaginator(int|string $contactId, ?PaymentMethodQuery $query = null): Paginator
    {
        $query = $query ?? PaymentMethodQuery::make();

        return new Paginator(
            fn (PaymentMethodQuery $q) => $this->list($contactId, $q),
            $query,
            'records'
        );
    }

    /**
     * Delete a payment method
     *
     * Permanently removes the specified payment method.
     *
     * @param  int  $contactId  Contact ID
     * @param  string  $paymentMethodId  Payment method ID
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function delete(int $contactId, string $paymentMethodId): bool
    {
        $response = $this->connector->send(new DeletePaymentMethod($contactId, $paymentMethodId));

        return $response->successful();
    }

    /**
     * Deactivate a payment method
     *
     * Deactivates the specified payment method without permanently deleting it.
     *
     * @param  int  $contactId  Contact ID
     * @param  string  $paymentMethodId  Payment method ID
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function deactivate(int $contactId, string $paymentMethodId): bool
    {
        $response = $this->connector->send(new DeactivatePaymentMethod($contactId, $paymentMethodId));

        return $response->successful();
    }

    /**
     * Create a new session key for adding payment methods
     *
     * Generates a session key to be used in the payment method embed component.
     *
     * @param  int  $contactId  Contact ID
     * @return array{session_key: string}
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException|\JsonException
     */
    public function newSessionKey(int $contactId): array
    {
        $response = $this->connector->send(new NewSessionKey($contactId));

        return $response->json();
    }

    public static function getEmbedScript(): string
    {
        return '<script src="https://payments.keap.page/lib/payment-method-embed.js"></script>';
    }

    /**
     * @param array{
     *     backgroundColor?: string,
     *     padding?: string,
     *     height?: string,
     *     fontSize?: string,
     *     borderRadius?: string,
     *     borderColor?: string,
     *     borderWidth?: string,
     *     borderStyle?: string,
     *     iconColor?: string,
     *     fontFamily?: string
     * }|null $dataStyles
     */
    public static function getEmbedComponent(string $sessionKey, ?array $dataStyles = null): string
    {
        $stylesString = '';
        if ($dataStyles !== null) {
            $stylesString = ' data-styles=\''.json_encode($dataStyles).'\'';
        }

        return '<keap-payment-method data-key="'.$sessionKey.'"'.$stylesString.'></keap-payment-method>';
    }

    /**
     * @param array{
     *     backgroundColor?: string,
     *     padding?: string,
     *     height?: string,
     *     fontSize?: string,
     *     borderRadius?: string,
     *     borderColor?: string,
     *     borderWidth?: string,
     *     borderStyle?: string,
     *     iconColor?: string,
     *     fontFamily?: string
     * }|null $dataStyles
     */
    public static function getEmbed(string $sessionKey, ?array $dataStyles = null): string
    {
        $script = self::getEmbedScript();
        $component = self::getEmbedComponent($sessionKey, $dataStyles);

        return $script."\n".$component;
    }
}
