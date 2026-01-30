<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Support;

use Psr\SimpleCache\CacheInterface;
use Toddstoker\KeapSdk\Support\Cache\ArrayCache;

final class Countries
{
    private const string CACHE_KEY = 'keap_sdk_countries';

    /**
     * @var array{
     *     countries: array<int, array<string, string>>,
     *     alpha2: array<string, int>,
     *     alpha3: array<string, int>,
     *     names: array<string, int>
     * }|null
     */
    private ?array $data = null;

    private CacheInterface $cache;

    public function __construct(?CacheInterface $cache = null)
    {
        $this->cache = $cache ?? new ArrayCache;
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function all(): array
    {
        return $this->getData()['countries'];
    }

    /**
     * @return array<string, string>|null
     */
    public function findByAlpha2(string $code): ?array
    {
        $data = $this->getData();
        $index = $data['alpha2'][strtolower($code)] ?? null;

        return $index !== null ? $data['countries'][$index] : null;
    }

    /**
     * @return array<string, string>|null
     */
    public function findByAlpha3(string $code): ?array
    {
        $data = $this->getData();
        $index = $data['alpha3'][strtolower($code)] ?? null;

        return $index !== null ? $data['countries'][$index] : null;
    }

    /**
     * @return array<string, string>|null
     */
    public function findByName(string $name): ?array
    {
        $data = $this->getData();
        $index = $data['names'][strtolower($name)] ?? null;

        return $index !== null ? $data['countries'][$index] : null;
    }

    /**
     * @return array<string, string>|null
     */
    public function findByAny(string $value): ?array
    {
        return match (strlen($value)) {
            2 => $this->findByAlpha2($value),
            3 => $this->findByAlpha3($value),
            default => $this->findByName($value),
        };
    }

    /**
     * @return array{
     *     countries: array<int, array<string, string>>,
     *     alpha2: array<string, int>,
     *     alpha3: array<string, int>,
     *     names: array<string, int>
     * }
     */
    private function getData(): array
    {
        if ($this->data !== null) {
            return $this->data;
        }

        /** @var array{countries: array<int, array<string, string>>, alpha2: array<string, int>, alpha3: array<string, int>, names: array<string, int>}|null $cached */
        $cached = $this->cache->get(self::CACHE_KEY);

        if ($cached !== null) {
            $this->data = $cached;

            return $this->data;
        }

        $json = file_get_contents(__DIR__.'/data/iso_3166-1.json');

        if ($json === false) {
            throw new \RuntimeException('Failed to read ISO 3166-1 data file.');
        }

        /** @var array<int, array<string, string>> $countries */
        $countries = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $alpha2 = [];
        $alpha3 = [];
        $names = [];

        foreach ($countries as $index => $country) {
            $alpha2[strtolower($country['alpha_2'])] = $index;
            $alpha3[strtolower($country['alpha_3'])] = $index;
            $names[strtolower($country['name'])] = $index;

            if (isset($country['official_name'])) {
                $names[strtolower($country['official_name'])] = $index;
            }
        }

        $this->data = [
            'countries' => $countries,
            'alpha2' => $alpha2,
            'alpha3' => $alpha3,
            'names' => $names,
        ];

        $this->cache->set(self::CACHE_KEY, $this->data);

        return $this->data;
    }
}
