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
     * @return mixed
     */
    public function remember(string $key, callable $fn)
    {
        if (!$this->cache->contains($key)) {
            $result = $fn();
            $this->cache->save($key, serialize($result));
        } else {
            $result = unserialize($this->cache->fetch($key));
        }

        return $result;
    }
}