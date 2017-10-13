<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Cache;

interface CacheHelper
{
    /**
     * @param string $key
     * @param callable $fn
     * @param int $ttl
     * @return mixed
     */
    public function remember(string $key, callable $fn, $ttl = 3600);

    /**
     * @param string $key
     */
    public function clear(string $key): void;

    /**
     * @param string $namespace
     * @param string $key
     * @param $data
     * @param int $ttl
     * @return bool
     */
    public function saveByNamespace(string $namespace, string $key, $data, int $ttl = 120): bool;

    /**
     * @param string $namespace
     * @param string $key
     * @return false|mixed
     */
    public function getByNamespace(string $namespace, string $key);

    /**
     * @param string $namespace
     * @return bool
     */
    public function clearNamespace(string $namespace): bool;

    public function purge(): void;
}