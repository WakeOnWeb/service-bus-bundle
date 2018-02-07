<?php

namespace WakeOnWeb\ServiceBusBundle\Infra\Bernard;

use Bernard\Producer as BernardProducer;
use Psr\Log\LoggerInterface;
use Prooph\ServiceBus\Async\MessageProducer;
use React\Promise\Deferred;
use Bernard\Message\PlainMessage;
use Prooph\Common\Messaging\Message;

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
        if ($this->logger) {
            $this->logger->debug(sprintf('[BERNARD PUBLISHER] Publishing to target %s, message : %s', $this->queueName, serialize($message)));
        }

        if (false === method_exists($message, 'toArray')) {
            throw new \InvalidArgumentException("Message should have a toArray method, inherits from DomainMessage to easily implement it.");
        }

        $this->producer->produce(new PlainMessage($this->queueName, $message->toArray()), $this->queueName);
    }
}
