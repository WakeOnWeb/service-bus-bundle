<?php

namespace WakeOnWeb\ServiceBusBundle\Infra\Bernard;

use Bernard\Message;
use Prooph\Common\Messaging\Message as Command;

/**
 * Contains a prooph command.
 * serialized/deserialized to works with queue.
 * useful internally because command code has to be the same
 * in producer/consumer app.
 *
 * @author Stephane PY <s.py@wakeonweb.com>
 */
class CommandMessage implements Message
{
    public function __construct($name, Command $command)
    {
        $this->name = $name;
        $this->command = $command;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'command' => serialize($this->command)
        ];
    }
}
