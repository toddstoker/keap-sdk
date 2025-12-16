<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V1;

use Toddstoker\KeapSdk\Keap;
use Toddstoker\KeapSdk\Requests\V1\Hooks\CreateHook;
use Toddstoker\KeapSdk\Requests\V1\Hooks\DelayedVerifyHook;
use Toddstoker\KeapSdk\Requests\V1\Hooks\DeleteHook;
use Toddstoker\KeapSdk\Requests\V1\Hooks\GetHook;
use Toddstoker\KeapSdk\Requests\V1\Hooks\ListEventKeys;
use Toddstoker\KeapSdk\Requests\V1\Hooks\ListHooks;
use Toddstoker\KeapSdk\Requests\V1\Hooks\UpdateHook;
use Toddstoker\KeapSdk\Requests\V1\Hooks\VerifyHook;
use Toddstoker\KeapSdk\Resources\Resource;

/**
 * Hooks Resource (v1)
 *
 * Provides methods for managing webhooks.
 *
 * @see https://developer.infusionsoft.com/docs/rest/
 */
readonly class HooksResource implements Resource
{
    public function __construct(protected Keap $connector) {}

    public function list(): array
    {
        return $this->connector->send(new ListHooks)->json();
    }

    public function get(string $key): array
    {
        return $this->connector->send(new GetHook($key))->json();
    }

    public function create(array $data): array
    {
        return $this->connector->send(new CreateHook($data))->json();
    }

    public function update(string $key, array $data): array
    {
        return $this->connector->send(new UpdateHook($key, $data))->json();
    }

    public function delete(string $key): bool
    {
        return $this->connector->send(new DeleteHook($key))->successful();
    }

    public function listEventKeys(): array
    {
        return $this->connector->send(new ListEventKeys)->json();
    }

    public function verify(string $key): bool
    {
        return $this->connector->send(new VerifyHook($key))->successful();
    }

    public function delayedVerify(string $key): bool
    {
        return $this->connector->send(new DelayedVerifyHook($key))->successful();
    }
}
