<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V2;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V2\Goals\Achieve;
use Toddstoker\KeapSdk\Resources\Resource;

/**
 * Goals Resource (v2)
 *
 * Provides methods for achieving automation goals in Keap.
 */
readonly class GoalsResource implements Resource
{
    public function __construct(
        protected Keap $connector
    ) {}

    /**
     * Achieve an automation goal for a contact
     *
     * Triggers the achievement of a specified automation goal.
     *
     * @param  int  $contactId  The contact ID
     * @param  string  $integration  The integration name
     * @param  string  $callName  The call name for the goal
     * @return array<string, mixed>
     *
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function achieve(int $contactId, string $integration, string $callName): array
    {
        $response = $this->connector->send(new Achieve($contactId, $integration, $callName));

        return $response->json();
    }
}
