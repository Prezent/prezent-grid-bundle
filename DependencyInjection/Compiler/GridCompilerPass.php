<?php

namespace Prezent\GridBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Process DIC tags
 *
 * @see CompilerPassInterface
 * @author Sander Marechal
 */
class GridCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->addGrids($container);
        $this->addGridExtensions($container);
    }

    /**
     * Add all grid types
     *
     * @param ContainerBuilder $container
     * @return void
     */
    private function addGrids(ContainerBuilder $container)
    {
        if (!$container->has('prezent_grid.grid_factory')) {
            return;
        }

        $grids = [];
        foreach ($container->findTaggedServices('prezent_grid.grid') as $id => $tags) {
            $grids[] = new Reference($id);
        }

        $container
            ->findDefinition('prezent_grid.grid_factory')
            ->replaceArgument(1, $extensions);
    }

    /**
     * Add all grid extensions
     *
     * @param ContainerBuilder $container
     * @return void
     */
    private function addGridExtensions(ContainerBuilder $container)
    {
        if (!$container->has('prezent_grid.column_type_factory')) {
            return;
        }

        $extensions = [];
        foreach ($container->findTaggedServices('prezent_grid.grid_extension') as $id => $tags) {
            $extensions[] = new Reference($id);
        }

        $container
            ->findDefinition('prezent_grid.column_type_factory')
            ->replaceArgument(0, $extensions);
    }
}
