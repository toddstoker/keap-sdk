<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Resources\V1;

use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
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

    /**
     * @return array<int, array{eventKey: string, hookUrl: string, key: string, status: string}>
     *
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function list(): array
    {
        return $this->connector->send(new ListHooks)->json();
    }

    /**
     * @return array{eventKey: string, hookUrl: string, key: string, status: string}
     * @phpstan-return array<string, mixed>
     *
     * @throws \JsonException
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $key): array
    {
        return $this->connector->send(new GetHook($key))->json();
    }

    /**
     * @param  array{eventKey?: string, hookUrl?: string}  $data
     * @return array{eventKey: string, hookUrl: string, key: string, status: string}
     * @phpstan-return array<string, mixed>
     *
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function create(array $data): array
    {
        return $this->connector->send(new CreateHook($data))->json();
    }

    /**
     * @param  array{eventKey?: string, hookUrl?: string}  $data
     * @return array{eventKey: string, hookUrl: string, key: string, status: string}
     * @phpstan-return array<string, mixed>
     *
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function update(string $key, array $data): array
    {
        return $this->connector->send(new UpdateHook($key, $data))->json();
    }

    public function delete(string $key): bool
    {
        return $this->connector->send(new DeleteHook($key))->successful();
    }

    /**
     * @return array<string>
     *
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function listEventKeys(): array
    {
        return $this->connector->send(new ListEventKeys)->json();
    }

    /**
     * @return array{eventKey: string, hookUrl: string, key: string, status: string}
     * @phpstan-return array<string, mixed>
     *
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function verify(string $key): array
    {
        return $this->connector->send(new VerifyHook($key))->json();
    }

    /**
     * @return array{eventKey: string, hookUrl: string, key: string, status: string}
     * @phpstan-return array<string, mixed>
     *
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function delayedVerify(string $key): array
    {
        /** @var array<string, mixed> $result */
        $result = $this->connector->send(new DelayedVerifyHook($key))->json();

        return $result;
    }
}
