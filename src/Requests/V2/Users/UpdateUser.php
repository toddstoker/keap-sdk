<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\Users;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update User (v2)
 *
 * Updates information on a specific user.
 *
 * Supports partial updates via the update_mask parameter.
 * If update_mask is provided, only the specified fields will be updated.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class UpdateUser extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    /**
     * @param  string  $userId  The user ID to update
     * @param  array<string, mixed>  $data  User data to update
     * @param  array<string>|null  $updateMask  Optional list of properties to update
     */
    public function __construct(
        protected readonly string $userId,
        protected readonly array $data,
        protected readonly ?array $updateMask = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/users/{$this->userId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        if ($this->updateMask === null) {
            return [];
        }

        return [
            'update_mask' => $this->updateMask,
        ];
    }
}
