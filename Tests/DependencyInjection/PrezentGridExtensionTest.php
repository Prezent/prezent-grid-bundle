<?php

namespace Prezent\GridBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Prezent\GridBundle\DependencyInjection\PrezentGridExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Sander Marechal
 */
class PrezentGridExtensionTest extends TestCase
{
    public function testDefault()
    {
        $container = new ContainerBuilder();

        $extension = new PrezentGridExtension();
        $extension->load([['themes' => ['foo', 'bar']]], $container);

        $this->assertInstanceOf(Definition::class, $container->findDefinition('grid_factory'));
        $this->assertEquals(['foo', 'bar'], $container->getParameter('prezent_grid.twig_renderer.themes'));
    }
}
