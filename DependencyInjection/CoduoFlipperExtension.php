<?php

namespace Coduo\FlipperBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

class CoduoFlipperExtension extends Extension
{
    /**
     * @var Factory\Strategy[]
     */
    private $factories;

    /**
     *
     */
    public function __construct()
    {
        $this->factories = $this->getFactories();
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration($this->factories);
        $config = $this->processConfiguration($configuration, $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $flipperDefinition = $container->getDefinition('coduo.flipper');

        foreach ($config['features'] as $name => $cfg) {
            $strategyKey = key($cfg);
            $id = sprintf("coduo.flipper.feature.%s.strategy.%s", $name, $strategyKey);
            $this->findStrategyByKey($strategyKey)->create($container, current($cfg), $id);

            $flipperDefinition->addMethodCall("add", [
                new Definition("Coduo\\Flipper\\Feature", [
                    $name,
                    new Reference($id)
                ])
            ]);
        }

        foreach ($config['contexts'] as $name => $cfg) {
            $flipperDefinition->addMethodCall("addContext", [
                new Definition("Coduo\\Flipper\\Activation\\Context", [$name])
            ]);
        }
    }

    /**
     * @throws \RuntimeException
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

    /**
     * @param $key
     * @return Factory\Strategy
     * @throws \RuntimeException
     */
    private function findStrategyByKey($key)
    {
        foreach ($this->factories as $factory) {
            if ($factory->getKey() === $key) {
                return $factory;
            }
        }

        throw new \RuntimeException(sprintf("Couldnt find strategy with key %s.", $key));
    }

}
