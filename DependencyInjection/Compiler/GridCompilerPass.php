<?php

namespace Prezent\GridBundle\DependencyInjection\Compiler;

use Prezent\Grid\Twig\GridExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
        $serviceIds = [
            $this->addGrids($container),
            $this->addTypes($container),
        ];

        if ($container->has('prezent_grid.extension.bundle')) {
            $serviceMap = [];

            \array_walk_recursive($serviceIds, function (string $id) use (&$serviceMap) {
                $serviceMap[$id] = new Reference($id);
            });

            $locator = ServiceLocatorTagPass::register($container, $serviceMap);

            $container
                ->findDefinition('prezent_grid.extension.bundle')
                ->replaceArgument(0, $locator);
        }

        $this->addGridExtensions($container);
        $this->addVariableResolvers($container);

        $reflClass = new \ReflectionClass(GridExtension::class);
        $container->findDefinition('twig.loader.filesystem')
            ->addMethodCall('addPath', array(dirname(dirname($reflClass->getFileName())).'/Resources/views/grid'));
    }

    /**
     * Add all grid types
     *
     * @param ContainerBuilder $container
     * @return array
     */
    private function addGrids(ContainerBuilder $container): array
    {
        if (!$container->has('prezent_grid.extension.bundle')) {
            return [];
        }

        $types = $this->findTypes($container, 'prezent_grid.grid');
        $extensions = $this->findTypeExtensions($container, 'prezent_grid.grid_type_extension');

        $container
            ->findDefinition('prezent_grid.extension.bundle')
            ->replaceArgument(1, $types)
            ->replaceArgument(2, $extensions);

        return [ $types, $extensions ];
    }

    /**
     * Add all element types and extensions
     *
     * @param ContainerBuilder $container
     * @return array
     */
    private function addTypes(ContainerBuilder $container): array
    {
        if (!$container->has('prezent_grid.extension.bundle')) {
            return [];
        }

        $types = $this->findTypes($container, 'prezent_grid.element_type');
        $extensions = $this->findTypeExtensions($container, 'prezent_grid.element_type_extension');

        $container
            ->findDefinition('prezent_grid.extension.bundle')
            ->replaceArgument(3, $types)
            ->replaceArgument(4, $extensions);

        return [ $types, $extensions ];
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

    /**
     * Find types using a tag
     *
     * @param string $tag
     * @return array
     */
    private function findTypes(ContainerBuilder $container, $tag)
    {
        $types = [];

        foreach ($container->findTaggedServiceIds($tag) as $id => $tags) {
            $definition = $container->getDefinition($id);

            // Support type access by FQCN
            $types[$definition->getClass()] = $id;
        }

        return $types;
    }

    /**
     * Find type extensions using a tag
     *
     * @param ContainerBuilder $container
     * @param string $tag
     * @return array
     */
    private function findTypeExtensions(ContainerBuilder $container, $tag)
    {
        $typeExtensions = [];

        foreach ($container->findTaggedServiceIds($tag) as $id => $tags) {
            $definition = $container->getDefinition($id);

            if (isset($tags[0]['extended_type'])) {
                $extendedType = $tags[0]['extended_type'];
            } else {
                throw new \InvalidArgumentException(
                    sprintf('Tagged grid type extension must have the extended type configured using the extended_type, none was configured for the "%s" service.', $id)
                );
            }

            $typeExtensions[$extendedType][] = $id;
        }

        return $typeExtensions;
    }
}
