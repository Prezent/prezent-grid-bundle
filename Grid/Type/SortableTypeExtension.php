<?php

namespace Prezent\GridBundle\Grid\Type;

use Prezent\Grid\BaseColumnTypeExtension;
use Prezent\Grid\ColumnView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Sortable columns
 *
 * @see BaseColumnTypeExtension
 * @author Sander Marechal
 */
class SortableTypeExtension extends BaseColumnTypeExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $sortFieldParameter;

    /**
     * @var string
     */
    private $sortOrderParameter;

    /**
     * Constructor
     *
     * @param UrlGeneratorInterface $router
     * @param RequestStack $requestStack
     * @param string $sortFieldParameter
     * @param string $sortOrderParameter
     */
    public function __construct(
        UrlGeneratorInterface $router,
        RequestStack $requestStack,
        $sortFieldParameter = 'sort_by',
        $sortOrderParameter = 'sort_order'
    ) {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->sortFieldParameter = $sortFieldParameter;
        $this->sortOrderParameter = $sortOrderParameter;
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
                'sort_field_parameter'  => $this->sortFieldParameter,
                'sort_order_parameter'  => $this->sortOrderParameter,
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

        $field = $options['sort_field'] ?: $view->name;
        $routeParams = $options['sort_route_parameters'] ?: $request->attributes->get('_route_params', []);

        if ($field === $request->get($this->sortFieldParameter)) {
            $order = 'ASC' === $request->get($this->sortOrderParameter) ? 'DESC' : 'ASC';
        } else {
            $order = 'ASC';
        }

        $routeParams[$this->sortFieldParameter] = $field;
        $routeParams[$this->sortOrderParameter] = $order;

        $view->vars['sort_route'] = $options['sort_route'] ?: $request->attributes->get('_route');
        $view->vars['sort_route_parameters'] = $routeParams;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return 'column';
    }
}
