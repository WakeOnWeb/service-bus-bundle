# Default command bus

```
wakeonweb_service_bus:
    command_buses:
        default: "synchronous_command_bus"
```

```
$bus = $container->get('wakeonweb.service_bus.command_bus_default');
```

# Map commands to bus via configuration


```
wakeonweb_service_bus:
    command_buses:
        default: "synchronous_command_bus"
        route_message_to_bus:
          "Acme\Foo\Command\RenameUser": "prooph_service_bus.my_specific_bus"
          # if command handled is not in this routing, it'll use default bus.
```

```
$command = Acme\Foo\Command\RenameUser::withData('uuid here ...', 'john', 'doe');
$bus     = $container->get('wakeonweb.service_bus.command_bus_guesser')->guessWithCommand($command);
$bus->handle($command);
```

[Back to home](../README.md)
