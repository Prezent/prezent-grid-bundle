<?php

declare(strict_types=1);

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
    public function __construct(
        private readonly VariableResolver $resolver
    )
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined(['url', 'route', 'route_parameters'])
            ->setAllowedTypes('route', ['string', 'Closure'])
            ->setAllowedTypes('route_parameters', 'array')
        ;
    }

    public function buildView(ElementView $view, array $options): void
    {
        if (isset($options['url']) || !isset($options['route'])) {
            return;
        }

        $view->vars['route'] = $options['route'];
        $view->vars['route_parameters'] = isset($options['route_parameters']) ? $options['route_parameters'] : [];
    }

    public function bindView(ElementView $view, mixed $item): void
    {
        if (!isset($view->vars['route_parameters'])) {
            return;
        }

        $view->vars['route'] = $this->resolver->resolve($view->vars['route'], $item);

        foreach ($view->vars['route_parameters'] as &$value) {
            $value = $this->resolver->resolve($value, $item);
        }
    }

    public function getExtendedType(): string
    {
        return ElementType::class;
    }
}
