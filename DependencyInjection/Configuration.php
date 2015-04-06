<?php

namespace Coduo\FlipperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var Strategy[]
     */
    private $factories;

    /**
     * @var \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    private $treeBuilder;

    /**
     * @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private $rootNode;

    public function __construct(array $factories)
    {
        $this->treeBuilder = new TreeBuilder();
        $this->rootNode = $this->treeBuilder->root('coduo_flipper');
        $this->factories = $factories;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $this->addFeaturesSection();
        $this->addContextSection();

        return $this->treeBuilder;
    }

    private function addFeaturesSection()
    {
        $strategyNodeBuilder = $this->rootNode
                ->fixXmlConfig('feature')
                ->children()
                    ->arrayNode('features')
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                        ->performNoDeepMerging()
                        ->children()
        ;

        foreach ($this->factories as $factory) {
            $factoryNode = $strategyNodeBuilder->arrayNode($factory->getKey())->canBeUnset();
            $factory->addConfiguration($factoryNode);
        }
    }

    private function addContextSection()
    {
        $this->rootNode
            ->fixXmlConfig('context')
            ->children()
                ->arrayNode('contexts')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->arrayNode('argument_resolvers')->treatNullLike(array())->prototype('scalar')->end()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
