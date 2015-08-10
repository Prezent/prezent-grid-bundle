<?php

namespace Prezent\GridBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle configuration
 *
 * @see ConfigurationInterface
 * @author Sander Marechal
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('prezent_grid');

        $rootNode
            ->children()
                ->scalarNode('theme')
                    ->defaultNull()
                    ->info('Grid theme twig template to use')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
