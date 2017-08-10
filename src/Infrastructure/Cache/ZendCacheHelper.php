<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Cache;

use Zend\Cache\Storage\StorageInterface;

class ZendCacheHelper implements CacheHelper
{
    private $cache;

    public function __construct(StorageInterface $cache)
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
        if (!$this->cache->hasItem($key)) {
            $result = $fn();
            $this->cache->setItem($key, $result);
        } else {
            $result = $this->cache->getItem($key);
        }

        return $result;
    }
}