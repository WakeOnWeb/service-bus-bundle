<?php

namespace WakeOnWeb\ServiceBusBundle\Infra\Bernard;

use Prooph\ServiceBus\MessageBus;

class Receiver
{
    private $messageBus;

    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(MessageEnvelope $message)
    {
        $this->messageBus->dispatch($message->getMessage());
    }
}
