<?php

namespace Prezent\GridBundle\Tests\Grid\Type;

use Prezent\Grid\ElementView;
use Prezent\Grid\VariableResolver;
use Prezent\GridBundle\Grid\Type\RouteTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RouteTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testSkipOnUrl()
    {
        $resolver = $this->getMock(VariableResolver::class);
        $extension = new RouteTypeExtension($resolver);

        $optionsResolver = new OptionsResolver();
        $extension->configureOptions($optionsResolver);

        $view = $this->getMockBuilder(ElementView::class)->disableOriginalConstructor()->getMock();
        $extension->buildView($view, [
            'url' => '/some/url',
            'route' => 'some_route',
        ]);

        $this->assertFalse(isset($view->vars['route']));
    }

    public function testResolver()
    {
        $resolver = $this->getMock(VariableResolver::class, ['resolve']);
        $resolver->method('resolve')->willReturn('1');

        $extension = new RouteTypeExtension($resolver);

        $optionsResolver = new OptionsResolver();
        $extension->configureOptions($optionsResolver);

        $view = $this->getMockBuilder(ElementView::class)->disableOriginalConstructor()->getMock();
        $extension->buildView($view, [
            'route' => 'some_route',
            'route_parameters' => [
                'id' => '{id}',
            ],
        ]);

        $extension->bindView($view, new \stdClass());

        $this->assertEquals('1', $view->vars['route_parameters']['id']);
    }
}
