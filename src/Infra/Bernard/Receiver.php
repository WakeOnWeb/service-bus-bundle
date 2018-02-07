<?php

namespace WakeOnWeb\ServiceBusBundle\Infra\Bernard;

use Prooph\ServiceBus\MessageBus;
use Bernard\Message\PlainMessage;
use Prooph\Common\Messaging\MessageFactory;

class Receiver
{
    private $messageBus;
    private $messageFactory;

    public function __construct(MessageBus $messageBus, MessageFactory $messageFactory)
    {
        $this->messageBus = $messageBus;
        $this->messageFactory = $messageFactory;
    }

    public function __invoke(PlainMessage $message)
    {
        $data = (array) $message->all();

        $message = $this->messageFactory->createMessageFromArray(
            $this->extractMessageNameFromData($data),
            $this->normalizeData($data)
        );

        $this->messageBus->dispatch($message);
    }

    private function extractMessageNameFromData(array $data)
    {
        if (false === array_key_exists('message_name', $data)) {
            throw new \InvalidArgumentException('Message data does not contains a message_name property.');
        }

        return $data['message_name'];
    }


    private function normalizeData(array $data)
    {
        if (is_array($data['created_at'])) {
            $data['created_at'] = $this->createDateTimeImmutableFromArray($data['created_at']);
        }

        return $data;
    }

    private function createDateTimeImmutableFromArray(array $date): \DateTimeImmutable
    {
        if (false === array_key_exists('date', $date) || false === array_key_exists('timezone', $date)) {
            throw new \InvalidArgumentException('date in messageData cannot be transformed to a \DateTimeImmutable instance.');
        }

        return new \DateTimeImmutable(
            $date['date'],
            new \DateTimezone($date['timezone'])
        );
    }
}
