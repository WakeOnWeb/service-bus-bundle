<?php

namespace WakeOnWeb\ServiceBusBundle\Infra\Bernard;

use Prooph\ServiceBus\CommandBus;

class Receiver
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(CommandMessage $message)
    {
        $this->commandBus->dispatch($message->getCommand());
    }
}
