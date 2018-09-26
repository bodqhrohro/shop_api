<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');

        $rootNode
            ->children()
                ->scalarNode('jwt_issuer')->end()
                ->scalarNode('jwt_audience')->end()
                ->integerNode('jwt_token_ttl')->end()
                ->scalarNode('jwt_secret_key')->end()
                ->scalarNode('jwt_public_key')->end()
            ->end();

        return $treeBuilder;
    }
}
