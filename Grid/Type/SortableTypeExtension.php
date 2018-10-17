<?php

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
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $fieldParameter;

    /**
     * @var string
     */
    private $orderParameter;

    /**
     * Constructor
     *
     * @param RequestStack $requestStack
     * @param string $fieldParameter
     * @param string $orderParameter
     */
    public function __construct(RequestStack $requestStack, $fieldParameter = 'sort_by', $orderParameter = 'sort_order')
    {
        $this->requestStack = $requestStack;
        $this->fieldParameter = $fieldParameter;
        $this->orderParameter = $orderParameter;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'sortable'              => false,
                'sort_field'            => null,
                'sort_route'            => null,
                'sort_route_parameters' => null,
                'sort_field_parameter'  => $this->fieldParameter,
                'sort_order_parameter'  => $this->orderParameter,
            ])
            ->setAllowedTypes('sortable', 'bool')
            ->setAllowedTypes('sort_field', ['null', 'string'])
            ->setAllowedTypes('sort_route', ['null', 'string'])
            ->setAllowedTypes('sort_route_parameters', ['null', 'array'])
            ->setAllowedTypes('sort_field_parameter', 'string')
            ->setAllowedTypes('sort_order_parameter', 'string')
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ElementView $view, array $options)
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
            $currentOrder = $request->get($options['sort_order_parameter']);
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

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return ColumnType::class;
    }
}
