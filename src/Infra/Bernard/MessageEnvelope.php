<?php

namespace WakeOnWeb\ServiceBusBundle\Infra\Bernard;

use Bernard\Message;
use Prooph\Common\Messaging\Message as ProophMessage;

/**
 * Contains a prooph message.
 * serialized/deserialized to works with queue.
 * useful internally because message code has to be the same
 * in producer/consumer app.
 *
 * @author Stephane PY <s.py@wakeonweb.com>
 */
class MessageEnvelope implements Message
{
    public function __construct($name, ProophMessage $message)
    {
        $this->name = $name;
        $this->message = $message;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'message' => serialize($this->message)
        ];
    }
}
