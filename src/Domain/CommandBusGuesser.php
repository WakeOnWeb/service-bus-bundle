<?php

namespace WakeOnWeb\ServiceBusBundle\Domain;

use Prooph\Common\Messaging\Command;
use Prooph\Bundle\ServiceBus\CommandBus;
use Psr\Container\ContainerInterface;

class CommandBusGuesser
{
    private $routes;
    private $container;

    public function __construct(array $routes = [], ContainerInterface $container)
    {
        $this->routes = $routes;
        $this->container = $container;
    }

    public function guessWithCommand(Command $command)
    {
        $bus = array_key_exists(get_class($command), $this->routes) ? $this->routes[get_class($command)] : 'wakeonweb.service_bus.command_bus_default';

        if (false === $this->container->has($bus)) {
            throw new Exception\UnknownCommandBusException(sprintf('Command bus %s for command %s is unknown.', $bus, get_class($command)));
        }

        return $this->container->get($bus);
    }
}
