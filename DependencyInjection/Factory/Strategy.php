<?php

namespace Coduo\FlipperBundle\DependencyInjection\Factory;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface Strategy
{
    /**     *
     * @return string
     */
    public function getKey();

    /**
     * @param ArrayNodeDefinition $node
     */
    public function addConfiguration(ArrayNodeDefinition $node);

    /**
     * @param ContainerBuilder $containerBuilder
     * @param array            $config
     * @param string           $id
     */
    public function create(ContainerBuilder $containerBuilder, array $config, $id);
}
