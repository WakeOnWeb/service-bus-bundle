ServiceBusBundle
================

Based on [prooph/service-bus-symfony-bundle](https://github.com/prooph/service-bus-symfony-bundle), this bundle add some extras to prooph service bus.


# Install

Add packages to `composer.json`

```
    "require": {
        "wakeonweb/service-bus-bundle": "^0.2.1",
        ..
    }
```

Register bundles in `AppKernel.php`

```
    new Prooph\Bundle\ServiceBus\ProophServiceBusBundle(),
    new WakeOnWeb\ServiceBusBundle\App\Bundle\WakeonwebServiceBusBundle()
```

Then, your `config.yml`.

```
prooph_service_bus:
  command_buses:
    synchronous_command_bus:
      router:
        type: 'prooph_service_bus.command_bus_router'
```

You can deal with prooph bus:

```
$bus = $container->get('prooph_service_bus.synchronous_command_bus');
$bus->dispatch(Acme\Foo\Command\RenameUser::withData('uuid here ...', 'john', 'doe'));
```

This was the prooph part, now:

- [Simple usage of prooph/service-bus](docs/example.md)
- [How to dispatch asynchronous commands](docs/asynchronous.md)
- [How to dispatch automatically commands to specific bus](docs/mapping.md)
