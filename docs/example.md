# Example

1) Let's create a command:

```
<?php

namespace Acme\Foo\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Prooph\ServiceBus\Async\AsyncMessage;

final class RenameUser extends Command implements PayloadConstructable, AsyncMessage
{
    use PayloadTrait;

    public static function withData($uuid, $firstname, $lastname)
    {
        return new self([
            'uuid' => $uuid,
            'firstname' => $firstname,
            'lastname' => $lastname
        ]);
    }
}
```

2) Create the handler:

/!\ The handler name and __invoke signature is important, since prooph give a message router which will automatically maps command to handler.
If you receive a message `CommandBus was not able to identify a CommandHandler for command Acme\Foo\Command\RenameUser` it's because:
    - You didn't register this handler has a service and tag it with command bus router.
    - Signature of __invoke method isn't valid.
    - Delete the cache, the route mapper is in a compiler pass.

```
<?php

namespace Acme\Foo\CommandHandler;

use Acme\Foo\Command\RenameUser;

final class RenameUserHandler
{
    public function __invoke(RenameUser $command)
    {
        var_dump($command->payload());
        // get the user from repository
        // rename it
        // save user on repositoryj
    }
}
```

3) Register the handler service

```
service_id_here:
    class: Acme\Foo\CommandHandler\RenameUserHandler
        public: true
        tags:
            # create tag for each command bus this handler should be registered.
            - { name: 'prooph_service_bus.synchronous_command_bus.route_target', message_detection: true }

```


*That's done*


```
$bus = $kernel->getContainer()->get('prooph_service_bus.synchronous_bus');
$bus->dispatch(Acme\Foo\Command\RenameUser::withData('uuid here ...', 'john', 'doe'));
```
