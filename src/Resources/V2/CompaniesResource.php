<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Companies\CreateCompany;
use Toddstoker\KeapSdk\Requests\V2\Companies\DeleteCompany;
use Toddstoker\KeapSdk\Requests\V2\Companies\GetCompany;
use Toddstoker\KeapSdk\Requests\V2\Companies\ListCompanies;
use Toddstoker\KeapSdk\Requests\V2\Companies\UpdateCompany;
use Toddstoker\KeapSdk\Resources\Resource;
use Toddstoker\KeapSdk\Support\V2\CompanyQuery;
use Toddstoker\KeapSdk\Support\V2\FieldSelector\CompanyFieldSelector;
use Toddstoker\KeapSdk\Support\V2\Paginator;

/**
 * Companies Resource (v2)
 *
 * Provides methods for interacting with the Keap Companies API v2.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class CompaniesResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * List companies with filtering, sorting, and pagination
     *
     * Returns a single page of results. Use newListPaginator() to automatically
     * iterate through all pages.
     *
     * @param  CompanyQuery|null  $query  Query builder with filters, sorting, and pagination options
     * @return array{
     *     companies: array<int, array{
     *         id: string,
     *         company_name?: string,
     *         email_address?: array{
     *             email: string,
     *             field: string,
     *             email_opt_status?: string,
     *             is_opt_in?: bool,
     *             opt_in_reason?: string
     *         },
     *         ...
     *     }>,
     *     next_page_token: ?string
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function list(?CompanyQuery $query = null): array
    {
        $query = $query ?? CompanyQuery::make();

        $response = $this->connector->send(
            new ListCompanies($query)
        );
        $data = $response->json();

        return [
            'companies' => $data['companies'] ?? [],
            'next_page_token' => $data['next_page_token'] ?? null,
        ];
    }

    /**
     * Create a paginator for iterating through the list companies endpoint.
     *
     * Automatically fetches subsequent pages using cursor-based pagination.
     *
     * @param  CompanyQuery|null  $query  Query builder with filters, sorting, and pagination options
     */
    public function newListPaginator(?CompanyQuery $query = null): Paginator
    {
        $query = $query ?? CompanyQuery::make();

        return new Paginator(
            fn (CompanyQuery $q) => $this->list($q),
            $query,
            'companies'
        );
    }

    /**
     * Get a specific company by ID
     *
     * Supports optional field selection. Pass an array of field names,
     * a CompanyFieldSelector instance, '*' for all fields, or null for default fields.
     *
     * @param  int  $companyId  The company ID
     * @param  CompanyFieldSelector|array<string>|string|null  $fields  Fields to include in response ('*' for all)
     * @return array{
     *     id: string,
     *     company_name?: string,
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
     *     email_address?: array{
     *         email: string,
     *         field: string,
     *         email_opt_status?: string,
     *         is_opt_in?: bool,
     *         opt_in_reason?: string
     *     },
     *     phone_number?: array{number: string, field: string, type?: string, extension?: string},
     *     fax_number?: array{number: string, field: string, type?: string},
     *     website?: string,
     *     notes?: string,
     *     custom_fields?: array<int, array{id: string, content: mixed}>,
     *     ...
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(int $companyId, CompanyFieldSelector|array|string|null $fields = null): array
    {
        $fieldSelector = CompanyFieldSelector::for($fields);

        $response = $this->connector->send(
            new GetCompany($companyId, $fieldSelector)
        );

        return $response->json();
    }

    /**
     * Create a new company
     *
     * Required field: company_name
     * Note: country_code is required if region is specified in address.
     *
     * @param  array{
     *     company_name: string,
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
     *     email_address?: array{
     *         email: string,
     *         field?: string,
     *         opt_in_reason?: string
     *     },
     *     phone_number?: array{number: string, field?: string, type?: string, extension?: string},
     *     fax_number?: array{number: string, field?: string, type?: string},
     *     website?: string,
     *     notes?: string,
     *     custom_fields?: array<int, array{id: string, content: mixed}>
     * }  $data  Company data
     * @return array{
     *     id: string,
     *     company_name?: string,
     *     email_address?: array{email: string, field: string, email_opt_status?: string}
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $data): array
    {
        $response = $this->connector->send(
            new CreateCompany($data)
        );

        return $response->json();
    }

    /**
     * Update an existing company
     *
     * Supports partial updates via the update_mask parameter to specify
     * which fields to update.
     *
     * @param  int  $companyId  The company ID to update
     * @param  array{
     *     company_name?: string,
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
     *     email_address?: array{
     *         email: string,
     *         field?: string,
     *         opt_in_reason?: string
     *     },
     *     phone_number?: array{number: string, field?: string, type?: string, extension?: string},
     *     fax_number?: array{number: string, field?: string, type?: string},
     *     website?: string,
     *     notes?: string,
     *     custom_fields?: array<int, array{id: string, content: mixed}>
     * }  $data  Company data to update
     * @param  array<string>|null  $updateMask  Optional list of properties to update
     * @return array{
     *     id: string,
     *     company_name?: string,
     *     email_address?: array{email: string, field: string, email_opt_status?: string},
     *     ...
     * }
     *
     * @phpstan-return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function update(int $companyId, array $data, ?array $updateMask = null): array
    {
        $response = $this->connector->send(
            new UpdateCompany($companyId, $data, $updateMask)
        );

        return $response->json();
    }

    /**
     * Delete a company
     *
     * Permanently deletes a company.
     *
     * @param  int  $companyId  The company ID to delete
     * @return bool True if deletion was successful
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function delete(int $companyId): bool
    {
        $response = $this->connector->send(
            new DeleteCompany($companyId)
        );

        return $response->successful();
    }
}
