<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\Web;

use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;

abstract class Controller
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Controller constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return CommandBus
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function commandBus(): CommandBus
    {
        return $this->getContainer()->get('CommandBus');
    }

    /**
     * @return CommandBus
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function cachedCommandBus(): CommandBus
    {
        return $this->getContainer()->get('CachedCommandBus');
    }
}