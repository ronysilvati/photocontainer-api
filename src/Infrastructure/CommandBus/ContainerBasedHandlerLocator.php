<?php

namespace PhotoContainer\PhotoContainer\Infrastructure\CommandBus;

use League\Tactician\Handler\Locator\HandlerLocator;
use Psr\Container\ContainerInterface;

class ContainerBasedHandlerLocator implements HandlerLocator
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * ContainerBasedHandlerLocator constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param string $commandName
     * @return mixed|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getHandlerForCommand($commandName)
    {
        $handlerId = str_replace(
            array('\\Command\\', 'Command'),
            array('\\Action\\', ''),
            $commandName
        );

        return $this->container->get($handlerId);
    }
}