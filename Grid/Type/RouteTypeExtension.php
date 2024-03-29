<?php

namespace Prezent\GridBundle\Grid\Type;

use Prezent\Grid\BaseElementTypeExtension;
use Prezent\Grid\ElementView;
use Prezent\Grid\Extension\Core\Type\ElementType;
use Prezent\Grid\VariableResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * generate URLs from routes
 *
 * @see BaseElementTypeExtension
 * @author Sander Marechal
 */
class RouteTypeExtension extends BaseElementTypeExtension
{
    /**
     * @var VariableResolver
     */
    private $resolver;

    /**
     * Constructor
     *
     * @param VariableResolver $resolver
     */
    public function __construct(VariableResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['url', 'route', 'route_parameters'])
            ->setAllowedTypes('route', ['string', 'Closure'])
            ->setAllowedTypes('route_parameters', 'array')
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ElementView $view, array $options)
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
    public function bindView(ElementView $view, $item)
    {
        if (!isset($view->vars['route_parameters'])) {
            return;
        }

        $view->vars['route'] = $this->resolver->resolve($view->vars['route'], $item);

        foreach ($view->vars['route_parameters'] as &$value) {
            $value = $this->resolver->resolve($value, $item);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return ElementType::class;
    }
}
