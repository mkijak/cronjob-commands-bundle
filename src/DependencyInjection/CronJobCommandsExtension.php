<?php

namespace Mkijak\CronJobCommandsBundle\DependencyInjection;

use Mkijak\CronJobCommandsBundle\CronJob\Config\Config;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CronJobCommandsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->loadServices($container);
        $this->processCSConfiguration($configs, $container);
    }

    private function processCSConfiguration(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $configDef = $container->getDefinition(Config::class);
        $configDef->replaceArgument('$timezone', $config['timezone']);
        $configDef->replaceArgument('$commands', $config['schedule']);
    }

    private function loadServices(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');
    }
}
