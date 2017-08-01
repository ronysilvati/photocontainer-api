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
     * @return mixed
     */
    public function remember(string $key, callable $fn)
    {
        if (!$this->cache->hasItem($key)) {
            $result = $fn();
            $this->cache->setItem($key, serialize($result));
        } else {
            $result = unserialize($this->cache->getItem($key));
        }

        return $result;
    }
}