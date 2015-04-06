<?php

namespace Coduo\FlipperBundle\DependencyInjection\Factory\Strategy;

use Coduo\FlipperBundle\DependencyInjection\Factory\Strategy;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class SystemWide implements Strategy
{
    public function getKey()
    {
        return 'system_wide';
    }

    public function create(ContainerBuilder $container, array $config, $id)
    {
        $container
            ->setDefinition($id, new Definition("Coduo\\Flipper\\Activation\\Strategy\\SystemWide", array($config['enabled'])))
            ->setPublic(false)
        ;
    }

    public function addConfiguration(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->booleanNode('enabled')->isRequired()->end()
            ->end()
        ;
    }
}
