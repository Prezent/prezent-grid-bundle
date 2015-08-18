<?php

namespace Prezent\GridBundle\Grid\Type;

use Prezent\Grid\BaseColumnTypeExtension;
use Prezent\Grid\ColumnView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Sortable columns
 *
 * @see BaseColumnTypeExtension
 * @author Sander Marechal
 */
class SortableTypeExtension extends BaseColumnTypeExtension
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
    public function configureOptions(OptionsResolverInterface $resolver)
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
            ->setAllowedTypes([
                'sortable'              => 'bool',
                'sort_field'            => ['null', 'string'],
                'sort_route'            => ['null', 'string'],
                'sort_route_parameters' => ['null', 'array'],
                'sort_field_parameter'  => 'string',
                'sort_order_parameter'  => 'string',
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ColumnView $view, array $options)
    {
        if (!$options['sortable'] || !($request = $this->requestStack->getCurrentRequest())) {
            return;
        }

        $active = false;
        $order = 'ASC';

        $field = $options['sort_field'] ?: $view->name;
        $routeParams = $options['sort_route_parameters'] ?: $request->attributes->get('_route_params', []);

        if ($field === $request->get($this->fieldParameter)) {
            $active = $request->get($this->orderParameter);
            $order = 'ASC' === $active ? 'DESC' : 'ASC';
        }

        $routeParams[$this->fieldParameter] = $field;
        $routeParams[$this->orderParameter] = $order;

        $view->vars['sort_route'] = $options['sort_route'] ?: $request->attributes->get('_route');
        $view->vars['sort_route_parameters'] = $routeParams;
        $view->vars['sort_active'] = $active;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return 'column';
    }
}