<?php

namespace Coduo\FlipperBundle\DependencyInjection\Factory;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CoduoFlipperExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $factories = $this->getFactories();
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
        $services = $container->findTaggedServiceIds('coduo.flipper.strategy.system_wide');

        foreach (array_keys($services) as $id) {
            $factory = $container->get($id);
            if (false === $factory instanceof Strategy) {
                throw new \RuntimeException("Strategy factories expect instance of Strategy.");
            }
            $factories[] = $factory;
        }

        return $factories;
    }
}
