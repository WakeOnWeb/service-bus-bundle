# Asynchronous bus with bernard.

```
prooph_service_bus:
  command_buses:
    synchronous_command_bus:
      router:
        type: 'prooph_service_bus.command_bus_router'
  asynchronous_command_bus:
    router:
      async_switch: 'wakeonweb.service_bus.async_commands.producer'

wakeonweb_service_bus:
    async_producers:
        async_commands:
            queue_name: commands
            receiver_bus: synchronous_command_bus
```

The command will be queued in a rabbitmq `commands` queue.

```
$bus = $container->get('prooph_service_bus.asynchronous_command_bus');
$bus->dispatch(Acme\Foo\Command\RenameUser::withData('uuid here ...', 'john', 'doe'));
```

To consume theses commands:

```
php bin/console bernard:consume commands --stop-on-error
```

Each commands consumed will be dispatched to bus `receiver_bus` (here synchronous_command_bus).



[Back to home](../README.md)
