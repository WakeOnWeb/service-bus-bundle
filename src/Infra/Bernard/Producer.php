<?php

namespace WakeOnWeb\ServiceBusBundle\Infra\Bernard;

use Bernard\Producer as BernardProducer;
use Psr\Log\LoggerInterface;
use Prooph\Common\Messaging\Message;
use Prooph\ServiceBus\Async\MessageProducer;
use React\Promise\Deferred;

class Producer implements MessageProducer
{
    private $producer;
    private $queueName;
    private $logger;

    public function __construct(BernardProducer $producer, $queueName, LoggerInterface $logger = null)
    {
        $this->producer = $producer;
        $this->queueName = $queueName;
        $this->logger = $logger;
    }

    public function __invoke(Message $message, Deferred $deferred = null): void
    {
        $message = new MessageEnvelope($this->queueName, $message);

        if ($this->logger) {
            $this->logger->debug(sprintf('[BERNARD PUBLISHER] Publishing to target %s, message : %s', $this->queueName, serialize($message)));
        }

        $this->producer->produce($message, $this->queueName);
    }
}
