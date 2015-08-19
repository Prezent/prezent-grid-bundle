<?php

namespace Prezent\GridBundle\Grid\Type;

use Prezent\Grid\BaseColumnTypeExtension;
use Prezent\Grid\ColumnView;
use Prezent\Grid\VariableResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Translate header labels
 *
 * @see BaseColumnTypeExtension
 * @author Sander Marechal
 */
class RouteTypeExtension extends BaseColumnTypeExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var VariableResolver
     */
    private $resolver;

    /**
     * Constructor
     *
     * @param UrlGeneratorInterface $router
     * @param VariableResolver $resolver
     */
    public function __construct(UrlGeneratorInterface $router, VariableResolver $resolver)
    {
        $this->router = $router;
        $this->resolver = $resolver;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefined(['url', 'route', 'route_parameters'])
            ->setAllowedTypes('route', 'string')
            ->setAllowedTypes('route_parameters', 'array')
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ColumnView $view, array $options)
    {
        if (isset($options['url']) || !isset($options['route'])) {
            return;
        }

        $view->vars['route'] = $options['route'];
        $view->vars['route_parameters'] = isset($options['route_parameters']) ? $options['route_parameters'] : [];
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(ColumnView $view, $item)
    {
        if (!isset($view->vars['route'])) {
            return;
        }

        foreach ($view->vars['route_parameters'] as &$value) {
            $value = $this->resolver->resolve($value, $item);
        }

        $view->vars['url'] = $this->router->generate($view->vars['route'], $view->vars['route_parameters']);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return 'element';
    }
}
