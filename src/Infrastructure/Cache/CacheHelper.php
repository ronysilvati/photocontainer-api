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
}