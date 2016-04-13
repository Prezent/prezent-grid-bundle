<?php

namespace Prezent\GridBundle\DependencyInjection\Compiler;

use Prezent\Grid\Twig\GridExtension;
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
        $this->addTypes($container);
        $this->addVariableResolvers($container);

        $reflClass = new \ReflectionClass(GridExtension::class);
        $container->getDefinition('twig.loader.filesystem')
            ->addMethodCall('addPath', array(dirname(dirname($reflClass->getFileName())).'/Resources/views/Grid'));
    }

    /**
     * Add all grid types
     *
     * @param ContainerBuilder $container
     * @return void
     */
    private function addGrids(ContainerBuilder $container)
    {
        if (!$container->has('prezent_grid.extension.bundle')) {
            return;
        }

        $types = [];
        foreach ($container->findTaggedServiceIds('prezent_grid.grid') as $id => $tags) {
            $types[] = new Reference($id);
        }

        $extensions = [];
        foreach ($container->findTaggedServiceIds('prezent_grid.grid_extension') as $id => $tags) {
            $extensions[] = new Reference($id);
        }

        $container
            ->findDefinition('prezent_grid.extension.bundle')
            ->replaceArgument(0, $types)
            ->replaceArgument(1, $extensions);
    }

    /**
     * Add all element types and extensions
     *
     * @param ContainerBuilder $container
     * @return void
     */
    private function addTypes(ContainerBuilder $container)
    {
        if (!$container->has('prezent_grid.extension.bundle')) {
            return;
        }

        $types = [];
        foreach ($container->findTaggedServiceIds('prezent_grid.element_type') as $id => $tags) {
            $types[] = new Reference($id);
        }

        $extensions = [];
        foreach ($container->findTaggedServiceIds('prezent_grid.element_type_extension') as $id => $tags) {
            $extensions[] = new Reference($id);
        }

        $container
            ->findDefinition('prezent_grid.extension.bundle')
            ->replaceArgument(2, $types)
            ->replaceArgument(3, $extensions);
    }

    /**
     * Add all grid extensions
     *
     * @param ContainerBuilder $container
     * @return void
     */
    private function addGridExtensions(ContainerBuilder $container)
    {
        if (!$container->has('prezent_grid.element_type_factory')) {
            return;
        }

        $extensions = [];
        foreach ($container->findTaggedServiceIds('prezent_grid.grid_extension') as $id => $tags) {
            $extensions[] = new Reference($id);
        }

        $container
            ->findDefinition('prezent_grid.grid_type_factory')
            ->replaceArgument(0, $extensions);

        $container
            ->findDefinition('prezent_grid.element_type_factory')
            ->replaceArgument(0, $extensions);
    }

    /**
     * Add all variable resolvers
     *
     * @param ContainerBuilder $container
     * @return void
     */
    private function addVariableResolvers(ContainerBuilder $container)
    {
        if (!$container->has('prezent_grid.variable_resolver')) {
            return;
        }

        $resolvers = [];
        foreach ($container->findTaggedServiceIds('prezent_grid.variable_resolver') as $id => $tags) {
            $resolvers[] = new Reference($id);
        }

        $container
            ->findDefinition('prezent_grid.variable_resolver')
            ->replaceArgument(0, $resolvers);
    }
}
