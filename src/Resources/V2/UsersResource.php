<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Users\GetUser;
use Toddstoker\KeapSdk\Requests\V2\Users\GetUserInfo;
use Toddstoker\KeapSdk\Requests\V2\Users\GetUserSignature;
use Toddstoker\KeapSdk\Requests\V2\Users\ListUsers;
use Toddstoker\KeapSdk\Requests\V2\Users\UpdateUser;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\Paginator;
use Toddstoker\KeapSdk\Support\V2\UserQuery;

/**
 * Users Resource (v2)
 *
 * Provides methods for interacting with the Keap Users API v2.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class UsersResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List users with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  UserQuery|null  $query  Query builder with filters and pagination options
     * @return array{
     *     users: array<int, array{
     *         id: string,
     *         given_name?: string,
     *         family_name?: string,
     *         email_addresses?: array<int, array{
     *             email: string,
     *             field: string,
     *             email_opt_status?: string,
     *             is_opt_in?: bool,
     *             opt_in_reason?: string
     *         }>,
     *         phone_numbers?: array<int, array{
     *             number: string,
     *             field: string,
     *             type?: string,
     *             extension?: string,
     *             number_e164?: string
     *         }>,
     *         fax_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *         address?: array{
     *             country?: string,
     *             country_code?: string,
     *             field?: string,
     *             line1?: string,
     *             line2?: string,
     *             locality?: string,
     *             postal_code?: string,
     *             region?: string,
     *             region_code?: string,
     *             zip_code?: string,
     *             zip_four?: string
     *         },
     *         social_accounts?: array<int, array{type: string, name?: string}>,
     *         admin?: bool,
     *         partner?: bool,
     *         status?: string,
     *         company_name?: string,
     *         title?: string,
     *         website?: string,
     *         global_user_id?: string,
     *         keap_id?: string,
     *         create_time?: string,
     *         update_time?: string,
     *         created_by?: int,
     *         updated_by?: int,
     *         ...
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?UserQuery $query = null): array
    {
        $query = $query ?? UserQuery::make();

        $response = $this->connector->send(new ListUsers($query));
        $data = $response->json();

        return [
            'users' => $data['users'] ?? [],
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through the list users endpoint.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  UserQuery|null  $query  Query builder with filters and pagination options
     */
    public function newListPaginator(?UserQuery $query = null): Paginator
    {
        $query = $query ?? UserQuery::make();

        return new Paginator(
            fn (UserQuery $q) => $this->list($q),
            $query,
            'users'
        );
    }

    /**
     * Get a specific user by ID
     *
     * @param  string  $userId  The user ID
     * @return array{
     *     id: string,
     *     given_name?: string,
     *     family_name?: string,
     *     email_addresses?: array<int, array{
     *         email: string,
     *         field: string,
     *         email_opt_status?: string,
     *         is_opt_in?: bool,
     *         opt_in_reason?: string
     *     }>,
     *     phone_numbers?: array<int, array{
     *         number: string,
     *         field: string,
     *         type?: string,
     *         extension?: string,
     *         number_e164?: string
     *     }>,
     *     fax_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     address?: array{
     *         country?: string,
     *         country_code?: string,
     *         field?: string,
     *         line1?: string,
     *         line2?: string,
     *         locality?: string,
     *         postal_code?: string,
     *         region?: string,
     *         region_code?: string,
     *         zip_code?: string,
     *         zip_four?: string
     *     },
     *     social_accounts?: array<int, array{type: string, name?: string}>,
     *     admin?: bool,
     *     partner?: bool,
     *     status?: string,
     *     company_name?: string,
     *     title?: string,
     *     website?: string,
     *     global_user_id?: string,
     *     keap_id?: string,
     *     create_time?: string,
     *     update_time?: string,
     *     created_by?: int,
     *     updated_by?: int,
     *     ...
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(string $userId): array
    {
        $response = $this->connector->send(new GetUser($userId));

        return $response->json();
    }

    /**
     * Update an existing user
     *
     * Updates information on a specific user. Supports partial updates via
     * the update_mask parameter to specify which fields to update.
     *
     * @param  string  $userId  The user ID to update
     * @param  array{
     *     given_name?: string,
     *     family_name?: string,
     *     email_address?: array{email: string, field: string, opt_in_reason?: string},
     *     phone_numbers?: array<int, array{number: string, field: string, type?: string, extension?: string}>,
     *     fax_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     address?: array{
     *         country?: string,
     *         country_code?: string,
     *         field?: string,
     *         line1?: string,
     *         line2?: string,
     *         locality?: string,
     *         postal_code?: string,
     *         region?: string,
     *         region_code?: string,
     *         zip_code?: string,
     *         zip_four?: string
     *     },
     *     company_name?: string,
     *     title?: string,
     *     website?: string,
     *     time_zone?: string,
     *     ...
     * }  $data  User data to update
     * @param  array<string>|null  $updateMask  Optional list of properties to update
     * @return array{
     *     id: string,
     *     given_name?: string,
     *     family_name?: string,
     *     email_addresses?: array<int, array{email: string, field: string, email_opt_status?: string}>,
     *     phone_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     fax_numbers?: array<int, array{number: string, field: string, type?: string}>,
     *     address?: array<string, mixed>,
     *     company_name?: string,
     *     title?: string,
     *     website?: string,
     *     create_time?: string,
     *     update_time?: string,
     *     ...
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function update(string $userId, array $data, ?array $updateMask = null): array
    {
        $response = $this->connector->send(
            new UpdateUser($userId, $data, $updateMask)
        );

        return $response->json();
    }

    /**
     * Get current authenticated user info
     *
     * Retrieves information for the current authenticated end-user.
     * This endpoint follows the OpenID Connect specification.
     *
     * @return array{
     *     id: string,
     *     sub: string,
     *     email?: string,
     *     given_name?: string,
     *     family_name?: string,
     *     middle_name?: string,
     *     preferred_name?: string,
     *     keap_id?: string,
     *     is_admin?: bool
     * }
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getUserInfo(): array
    {
        $response = $this->connector->send(new GetUserInfo);

        return $response->json();
    }

    /**
     * Get user email signature
     *
     * Retrieves a HTML snippet that contains the user's email signature.
     *
     * @param  string  $userId  The user ID
     * @return array{signature?: string} Response containing the user's email signature
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getSignature(string $userId): array
    {
        $response = $this->connector->send(new GetUserSignature($userId));

        return $response->json();
    }
}
