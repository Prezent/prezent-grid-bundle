<?php

declare(strict_types=1);

namespace Prezent\GridBundle\Grid\Type;

use Prezent\Grid\BaseElementTypeExtension;
use Prezent\Grid\ElementView;
use Prezent\Grid\Extension\Core\Type\ColumnType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Sortable columns
 *
 * @see BaseElementTypeExtension
 * @author Sander Marechal
 */
class SortableTypeExtension extends BaseElementTypeExtension
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly string $fieldParameter = 'sort_by',
        private readonly string $orderParameter = 'sort_order'
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'sortable' => false,
                'sort_field' => null,
                'sort_route' => null,
                'sort_route_parameters' => null,
                'sort_field_parameter' => $this->fieldParameter,
                'sort_order_parameter' => $this->orderParameter,
            ])
            ->setAllowedTypes('sortable', 'bool')
            ->setAllowedTypes('sort_field', ['null', 'string'])
            ->setAllowedTypes('sort_route', ['null', 'string'])
            ->setAllowedTypes('sort_route_parameters', ['null', 'array'])
            ->setAllowedTypes('sort_field_parameter', 'string')
            ->setAllowedTypes('sort_order_parameter', 'string')
        ;
    }

    public function buildView(ElementView $view, array $options): void
    {
        if (!$options['sortable'] || !($request = $this->requestStack->getCurrentRequest())) {
            return;
        }

        $active = false;
        $order = 'ASC';

        $sortField = $options['sort_field'] ?: $view->name;
        $routeParams = $options['sort_route_parameters'] ?: $request->attributes->get('_route_params', []);
        $routeParams = array_merge($routeParams, $request->query->all());

        if ($sortField === $request->get($options['sort_field_parameter'])) {
            $active = true;
            $currentOrder = $request->get($options['sort_order_parameter'], 'ASC');
            $order = 'ASC' === $currentOrder ? 'DESC' : 'ASC';
        }

        $routeParams[$options['sort_field_parameter']] = $sortField;
        $routeParams[$options['sort_order_parameter']] = $order;

        $view->vars['sort_route'] = $options['sort_route'] ?: $request->attributes->get('_route');
        $view->vars['sort_route_parameters'] = $routeParams;
        $view->vars['sort_active'] = $active;

        if ($active) {
            $view->vars['sort_order'] = $currentOrder;
        }
    }

    public function getExtendedType(): string
    {
        return ColumnType::class;
    }
}
