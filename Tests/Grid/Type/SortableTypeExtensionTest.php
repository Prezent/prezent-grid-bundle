<?php

namespace Prezent\GridBundle\Tests\Grid\Type;

use PHPUnit\Framework\TestCase;
use Prezent\GridBundle\Grid\Type\SortableTypeExtension;
use Prezent\Grid\ElementView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Sander Marechal
 */
class SortableTypeExtensionTest extends TestCase
{
    private $defaultOptions = [
        'sortable'              => true,
        'sort_field'            => null,
        'sort_route'            => null,
        'sort_route_parameters' => null,
        'sort_field_parameter'  => 'sort_by',
        'sort_order_parameter'  => 'sort_order',
    ];

    public function testDefaults()
    {
        $requestStack = $this->createMock(RequestStack::class);
        $optionsResolver = new OptionsResolver();

        $extension = new SortableTypeExtension($requestStack);
        $extension->configureOptions($optionsResolver);

        foreach ($this->defaultOptions as $key => $value) {
            $this->assertTrue($optionsResolver->hasDefault($key));
        }

        $this->assertEmpty($optionsResolver->getMissingOptions());
    }

    /**
     * @dataProvider sortingprovider
     */
    public function testBuildView($request, $options, $expected)
    {
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getCurrentRequest')->willReturn($request);

        $view = $this->createMock(ElementView::class);
        $view->name = 'field';

        $extension = new SortableTypeExtension($requestStack);
        $extension->buildView($view, $options);

        $this->assertEquals($expected, $view->vars);
    }

    public function sortingProvider()
    {
        $data = [];

        $data['Not sortable'] = [
            null,
            $this->defaultOptions,
            [],
        ];

        $data['No request'] = [
            null,
            array_merge($this->defaultOptions, ['sortable' => true]),
            [],
        ];

        $data['Inactive sort field'] = [
            new Request([], [], [
                '_route' => 'my_route',
                '_route_params' => [],
            ]),
            array_merge($this->defaultOptions, ['sortable' => true]),
            [
                'sort_active' => false,
                'sort_route' => 'my_route',
                'sort_route_parameters' => [
                    'sort_by' => 'field',
                    'sort_order' => 'ASC',
                ],
            ],
        ];

        $data['Active sort field'] = [
            new Request([
                'sort_by' => 'field',
                'sort_order' => 'ASC'
            ], [], [
                '_route' => 'my_route',
                '_route_params' => [],
            ]),
            array_merge($this->defaultOptions, ['sortable' => true]),
            [
                'sort_order' => 'ASC',
                'sort_active' => true,
                'sort_route' => 'my_route',
                'sort_route_parameters' => [
                    'sort_by' => 'field',
                    'sort_order' => 'DESC',
                ],
            ],
        ];

        $data['Preserve route and query parameters'] = [
            new Request(['query' => 'foo'], [], [
                '_route' => 'my_route',
                '_route_params' => ['id' => '1'],
            ]),
            array_merge($this->defaultOptions, ['sortable' => true]),
            [
                'sort_active' => false,
                'sort_route' => 'my_route',
                'sort_route_parameters' => [
                    'id' => '1',
                    'query' => 'foo',
                    'sort_by' => 'field',
                    'sort_order' => 'ASC',
                ],
            ],
        ];

        $data['Override options'] = [
            new Request([], [], [
                '_route' => 'my_route',
                '_route_params' => [],
            ]),
            [
                'sortable'              => true,
                'sort_field'            => 'custom_field',
                'sort_route'            => 'custom_route',
                'sort_route_parameters' => ['custom_parameter' => '1'],
                'sort_field_parameter'  => 'by',
                'sort_order_parameter'  => 'order',
            ],
            [
                'sort_active' => false,
                'sort_route' => 'custom_route',
                'sort_route_parameters' => [
                    'by' => 'custom_field',
                    'custom_parameter' => '1',
                    'order' => 'ASC',
                ],
            ],
        ];

        return $data;
    }
}
