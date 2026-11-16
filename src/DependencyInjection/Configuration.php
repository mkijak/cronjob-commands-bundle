<?php

namespace Mkijak\CronJobCommandsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('cron_job_commands');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('timezone')->defaultNull()->end()
                ->arrayNode('schedule')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->cannotBeEmpty()->end()
                            ->booleanNode('enabled')->defaultTrue()->end()
                            ->scalarNode('cron_expression')->cannotBeEmpty()->end()
                            ->arrayNode('arguments')
                                ->prototype('scalar')->end()
                            ->end()
                            // option value: a scalar or an array of scalars (for options accepting multiple values),
                            // given directly or as an env placeholder, e.g. '%env(json:VARIABLE)%';
                            // values are validated when the config is loaded, as env placeholders resolve at runtime
                            ->arrayNode('options')
                                ->prototype('variable')->end()
                            ->end()
                            // deprecated, to be removed in 3.0 - options accept arrays of values as well
                            ->arrayNode('multivalue_options')
                                ->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
