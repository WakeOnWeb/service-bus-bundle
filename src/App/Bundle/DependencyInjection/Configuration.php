<?php

namespace WakeOnWeb\ServiceBusBundle\App\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $tb       = new TreeBuilder();
        $rootNode = $tb->root('wakeonweb_service_bus');

        $rootNode
            ->children()
                ->arrayNode('command_buses')
                    ->children()
                        ->scalarNode('default')->end()
                        ->arrayNode('route_message_to_bus')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('async_producers')
                    ->useAttributeAsKey('key')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('queue_name')->isRequired()->end()
                            ->scalarNode('receiver_bus')->end()
                        ->end()
                    ->end()
                ->end()
            ;

        return $tb;
    }
}
