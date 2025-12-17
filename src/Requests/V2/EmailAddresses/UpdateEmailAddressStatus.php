<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Requests\V2\EmailAddresses;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Email Address Status (v2)
 *
 * Updates an Email Address opt-in status.
 *
 * You may opt-in or mark an email address as Marketable by providing an opt-in reason.
 * The reason helps with compliance (e.g., "Customer opted-in through webform").
 *
 * Note: Email address status will only be updated to Unconfirmed (marketable) for addresses
 * currently in: Unengaged Marketable, Unengaged Non-Marketable, Non-Marketable, or Opt-Out: Manual.
 * All other statuses will remain non-marketable.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
class UpdateEmailAddressStatus extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected readonly string $email,
        protected readonly bool $optedIn,
        protected readonly string $reason
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v2/emailAddresses/{$this->email}/status";
    }

    protected function defaultBody(): array
    {
        return [
            'opted_in' => $this->optedIn,
            'reason' => $this->reason,
        ];
    }
}
