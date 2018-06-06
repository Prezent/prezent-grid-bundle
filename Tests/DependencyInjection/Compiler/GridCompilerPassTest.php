<?php

namespace Prezent\GridBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Prezent\GridBundle\DependencyInjection\Compiler\GridCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Sander Marechal
 */
class GridCompilerPassTest extends TestCase
{
    public function testTwigPath()
    {
        $twig = $this->getMockBuilder(Definition::class)
            ->setMethods(['addMethodCall'])
            ->getMock();

        $twig->expects($this->once())
            ->method('addMethodCall')
            ->with('addPath', $this->anything());

        $builder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['findDefinition'])
            ->getMock();

        $builder->method('findDefinition')->willReturn($twig);

        $pass = new GridCompilerPass();
        $pass->process($builder);
    }
}
