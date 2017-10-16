<?php

namespace Prezent\GridBundle\DependencyInjection;

use Prezent\Grid;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Load the bundle configuration
 *
 * @see Extension
 * @author Sander Marechal
 */
class PrezentGridExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (method_exists($container, 'registerForAutoconfiguration')) {
            $container->registerForAutoconfiguration(Grid\GridType::class)
                ->addTag('prezent_grid.grid');
            $container->registerForAutoconfiguration(Grid\GridTypeExtension::class)
                ->addTag('prezent_grid.grid_type_extension');
            $container->registerForAutoconfiguration(Grid\ElementType::class)
                ->addTag('prezent_grid.element_type');
            $container->registerForAutoconfiguration(Grid\ElementTypeExtension::class)
                ->addTag('prezent_grid.element_type_extension');
            $container->registerForAutoconfiguration(Grid\GridExtension::class)
                ->addTag('prezent_grid.grid_extension');
            $container->registerForAutoconfiguration(Grid\ElementTypeFactory::class)
                ->addTag('prezent_grid.element_type_factory');
            $container->registerForAutoconfiguration(Grid\VariableResolver::class)
                ->addTag('prezent_grid.variable_resolver');
        }

        if ($config['themes']) {
            $container->setParameter('prezent_grid.twig_renderer.themes', $config['themes']);
        }
    }
}
