<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\CommandBus\Middleware;

use League\Tactician\Middleware;
use PhotoContainer\PhotoContainer\Infrastructure\Cache\CacheHelper;
use Psr\Container\ContainerInterface;

class HandlerCacheMiddleware implements Middleware
{
    const TTL_DAY_SECONDS = 86400;

    /**
     * @var CacheHelper
     */
    private $cacheHelper;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * HandlerCacheMiddleware constructor.
     * @param ContainerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->cacheHelper = $container->get(CacheHelper::class);
        $this->cacheKey = md5($container->get('request')->getUri()->getPath());
    }

    /**
     * @param object $command
     * @param callable $next
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        return $this->cacheHelper->remember($this->cacheKey, function () use ($command, $next) {
            return $next($command);
        }, self::TTL_DAY_SECONDS);
    }
}