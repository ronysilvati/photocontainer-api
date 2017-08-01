<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Cache;

interface CacheHelper
{
    /**
     * @param string $key
     * @param callable $fn
     * @return mixed
     */
    public function remember(string $key, callable $fn);
}