<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\EmailAddresses\GetEmailAddressStatus;
use Toddstoker\KeapSdk\Requests\V2\EmailAddresses\UpdateEmailAddressStatus;
use Toddstoker\KeapSdk\Resources\Resource;

/**
 * Email Addresses Resource (v2)
 *
 * Provides methods for managing email address opt-in status.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
readonly class EmailAddressesResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {
    }

    /**
     * Get email address status
     *
     * Retrieves the opt-in status for a given Email Address.
     *
     * @param string $email The email address
     * @return array{email: string, opted_in: bool, status: string}
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function getStatus(string $email): array
    {
        $response = $this->connector->send(new GetEmailAddressStatus($email));

        return $response->json();
    }

    /**
     * Update email address opt-in status
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
     * @param string $email The email address
     * @param bool $optedIn Whether the email is opted in
     * @param string $reason Reason for the opt-in/opt-out change (required for compliance)
     * @return array{email: string, opted_in: bool, status: string}
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function updateStatus(string $email, bool $optedIn, string $reason): array
    {
        $response = $this->connector->send(
            new UpdateEmailAddressStatus($email, $optedIn, $reason)
        );

        return $response->json();
    }
}
