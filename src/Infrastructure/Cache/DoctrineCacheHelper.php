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
}