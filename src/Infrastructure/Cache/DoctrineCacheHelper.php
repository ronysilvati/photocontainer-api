<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Cache;

use Doctrine\Common\Cache\CacheProvider;

class DoctrineCacheHelper implements CacheHelper
{
    /**
     * @var CacheProvider
     */
    private $cache;

    /**
     * DoctrineCacheHelper constructor.
     * @param CacheProvider $cache
     */
    public function __construct(CacheProvider $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $key
     * @param callable $fn
     * @param int $ttl
     * @return mixed
     */
    public function remember(string $key, callable $fn, $ttl = 3600)
    {
        if (!$this->cache->contains($key)) {
            $result = $fn();
            $this->cache->save($key, $result, $ttl);
        } else {
            $result = $this->cache->fetch($key);
        }

        return $result;
    }

    /**
     * @param string $namespace
     * @param string $key
     * @param $data
     * @param int $ttl
     * @return bool
     */
    public function saveByNamespace(string $namespace, string $key, $data, int $ttl = 3600): bool
    {
        $this->cache->setNamespace($namespace);
        return $this->cache->save($key, $data, $ttl);
    }

    /**
     * @param string $namespace
     * @param string $key
     * @return false|mixed
     */
    public function getByNamespace(string $namespace, string $key)
    {
        $this->cache->setNamespace($namespace);
        return $this->cache->fetch($key);
    }

    /**
     * @param string $namespace
     * @return bool
     */
    public function clearNamespace(string $namespace): bool
    {
        $this->cache->setNamespace($namespace);
        return $this->cache->deleteAll();
    }

    /**
     * @param string $key
     */
    public function clear(string $key): void
    {
        $this->cache->delete($key);
    }

    public function purge(): void
    {
        $this->cache->deleteAll();
    }
}