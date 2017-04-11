<?php

namespace BeerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class BeerExtension.
 */
class BeerExtension extends Extension
{
    /**
     * Bundle directory.
     *
     * @var string
     */
    public $location = __DIR__;

    /**
     * @return Configuration
     */
    public function configuration()
    {
        return new Configuration();
    }

    /**
     * Load additional configuration to container.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->processConfiguration($this->configuration(), $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(sprintf('%s/../Resources/config/', $this->location))
        );

        $loader->load('config.yml');
    }
}
