<?php

namespace Prezent\GridBundle\Tests\Grid\Type;

use Prezent\Grid\ColumnView;
use Prezent\Grid\VariableResolver;
use Prezent\GridBundle\Grid\Type\RouteTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RouteTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testSkipOnUrl()
    {
        $router = $this->getMock(UrlGeneratorInterface::class);
        $resolver = $this->getMock(VariableResolver::class);
        $extension = new RouteTypeExtension($router, $resolver);

        $optionsResolver = new OptionsResolver();
        $extension->configureOptions($optionsResolver);

        $view = $this->getMockBuilder(ColumnView::class)->disableOriginalConstructor()->getMock();
        $extension->buildView($view, [
            'url' => '/some/url',
            'route' => 'some_route',
        ]);

        $this->assertFalse(isset($view->vars['route']));
    }

    public function testUrlGeneration()
    {
        $router = $this->getMock(UrlGeneratorInterface::class);
        $router->method('generate')->will($this->returnCallback(function ($route, $params) {
            return $route . '?' . http_build_query($params);
        }));

        $resolver = $this->getMock(VariableResolver::class, ['resolve']);
        $resolver->method('resolve')->willReturn('1');

        $extension = new RouteTypeExtension($router, $resolver);

        $optionsResolver = new OptionsResolver();
        $extension->configureOptions($optionsResolver);

        $view = $this->getMockBuilder(ColumnView::class)->disableOriginalConstructor()->getMock();
        $extension->buildView($view, [
            'route' => 'some_route',
            'route_parameters' => [
                'id' => '{id}',
            ],
        ]);

        $extension->bindView($view, new \stdClass());

        $this->assertEquals('some_route?id=1', $view->vars['url']);
    }
}
