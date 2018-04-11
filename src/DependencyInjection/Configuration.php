<?php

namespace Mkijak\CronJobCommandsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cron_job_commands');

        $rootNode
            ->children()
                ->scalarNode('timezone')->defaultNull()->end()
                ->arrayNode('schedule')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->cannotBeEmpty()->end()
                            ->scalarNode('cron_expression')->cannotBeEmpty()->end()
                            ->arrayNode('arguments')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('options')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
