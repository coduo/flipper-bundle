<?php

namespace Coduo\FlipperBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CoduoFlipperExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $factories = $this->getFactories();
        $configuration = new Configuration($factories);
        $config = $this->processConfiguration($configuration, $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @return array
     */
    private function getFactories()
    {
        $container = new ContainerBuilder();
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('strategy_factories.yml');
        $factories = [];
        $services = $container->findTaggedServiceIds('coduo.flipper.strategy_factory');

        foreach (array_keys($services) as $id) {
            $factory = $container->get($id);
            if (false === $factory instanceof Factory\Strategy) {
                throw new \RuntimeException("Strategy factories expect instance of Factory\\Strategy.");
            }
            $factories[] = $factory;
        }

        return $factories;
    }
}
