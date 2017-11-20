<?php

namespace WakeOnWeb\ServiceBusBundle\App\Bundle\DependencyInjection;

/* Imports */
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use WakeOnWeb\ServiceBusBundle\Infra\Bernard\Producer;
use WakeOnWeb\ServiceBusBundle\Infra\Bernard\Receiver;

class WakeonwebServiceBusExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));
        $loader->load('bernard.xml');
        $loader->load('guesser.xml');

        if (array_key_exists('command_buses', $config)) {
            $commandBusConfig = $config['command_buses'];

            if (array_key_exists('default', $commandBusConfig)) {
                $container->setAlias('wakeonweb.service_bus.command_bus_default', sprintf('prooph_service_bus.%s', $commandBusConfig['default']));
            }

            $container->getDefinition('wakeonweb.service_bus.command_bus_guesser')->replaceArgument(0, $commandBusConfig['route_message_to_bus']);
        }

        foreach ($config['async_producers'] as $name => $options) {
            $container->setDefinition(sprintf('wakeonweb.service_bus.%s.producer', $name) , new Definition(
                Producer::class, [
                    new Reference('bernard.producer'),
                    $options['queue_name'],
                    new Reference('logger', ContainerInterface::NULL_ON_INVALID_REFERENCE),
                ]
            ));

            if ($options['receiver_bus']) {
                $definition = new Definition(Receiver::class, [
                    new Reference(sprintf('prooph_service_bus.%s', $options['receiver_bus']))
                ]);
                $definition->addTag('bernard.receiver', ['message' => $options['queue_name']]);

                $container->setDefinition(sprintf('wakeonweb.service_bus.%s.receiver', $name), $definition);
            }
        }
    }
}
