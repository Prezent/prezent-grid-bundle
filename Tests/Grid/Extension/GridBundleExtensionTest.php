<?php

namespace Prezent\GridBundle\Tests\Grid\Extension;

use PHPUnit\Framework\TestCase;
use Prezent\Grid\BaseElementType;
use Prezent\Grid\BaseGridType;
use Prezent\Grid\ElementTypeExtension;
use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\GridBundle\Grid\Extension\GridBundleExtension;
use Prezent\Grid\GridTypeExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Sander Marechal
 */
class GridBundleExtensionTest extends TestCase
{
    public function testGridTypes()
    {
        $container = $this->createMock(ContainerInterface::class);
        $grid = $this->createMock(BaseGridType::class);

        $container
            ->method('get')
            ->will($this->returnValueMap([
                ['exists', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $grid],
            ]));

        $extension = new GridBundleExtension($container, ['Some\\Grid' => 'exists'], [], [], []);

        $this->assertTrue($extension->hasGridType('Some\\Grid'));
        $this->assertEquals($grid, $extension->getGridType('Some\\Grid'));

        $this->assertFalse($extension->hasGridType('Other\\Grid'));

        $this->expectException(InvalidArgumentException::class);
        $extension->getGridType('Other\\Grid');
    }

    public function testGridTypeExtensions()
    {
        $typeExtension = $this->createMock(GridTypeExtension::class);
        $typeExtension->method('getExtendedType')->willReturn('Some\\Grid');

        $container = $this->createMock(ContainerInterface::class);

        $container
            ->method('get')
            ->will($this->returnValueMap([
                ['exists', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $typeExtension],
            ]));

        $extension = new GridBundleExtension($container, [], ['Some\\Grid' => ['exists']], [], []);

        $this->assertEquals([$typeExtension], $extension->getGridTypeExtensions('Some\\Grid'));
        $this->assertEquals([], $extension->getGridTypeExtensions('Other\\Grid'));
    }

    public function testBadGridTypeExtension()
    {
        $typeExtension = $this->createMock(GridTypeExtension::class);
        $typeExtension->method('getExtendedType')->willReturn('Other\\Grid');

        $container = $this->createMock(ContainerInterface::class);

        $container
            ->method('get')
            ->will($this->returnValueMap([
                ['exists', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $typeExtension],
            ]));

        $extension = new GridBundleExtension($container, [], ['Some\\Grid' => ['exists']], [], []);

        $this->expectException(InvalidArgumentException::class);
        $extension->getGridTypeExtensions('Some\\Grid');
    }

    public function testElementTypes()
    {
        $container = $this->createMock(ContainerInterface::class);
        $elementType = $this->createMock(BaseElementType::class);

        $container
            ->method('get')
            ->will($this->returnValueMap([
                ['exists', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $elementType],
            ]));

        $extension = new GridBundleExtension($container, [], [], ['Some\\Element' => 'exists'], []);

        $this->assertTrue($extension->hasElementType('Some\\Element'));
        $this->assertEquals($elementType, $extension->getElementType('Some\\Element'));

        $this->assertFalse($extension->hasElementType('Other\\Element'));

        $this->expectException(InvalidArgumentException::class);
        $extension->getElementType('Other\\Element');
    }

    public function testElementTypeExtensions()
    {
        $typeExtension = $this->createMock(ElementTypeExtension::class);
        $typeExtension->method('getExtendedType')->willReturn('Some\\Element');

        $container = $this->createMock(ContainerInterface::class);

        $container
            ->method('get')
            ->will($this->returnValueMap([
                ['exists', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $typeExtension],
            ]));

        $extension = new GridBundleExtension($container, [], [], [], ['Some\\Element' => ['exists']]);

        $this->assertEquals([$typeExtension], $extension->getElementTypeExtensions('Some\\Element'));
        $this->assertEquals([], $extension->getElementTypeExtensions('Other\\Element'));
    }

    public function testBadElementTypeExtension()
    {
        $typeExtension = $this->createMock(ElementTypeExtension::class);
        $typeExtension->method('getExtendedType')->willReturn('Other\\Element');

        $container = $this->createMock(ContainerInterface::class);

        $container
            ->method('get')
            ->will($this->returnValueMap([
                ['exists', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $typeExtension],
            ]));

        $extension = new GridBundleExtension($container, [], [], [], ['Some\\Element' => ['exists']]);

        $this->expectException(InvalidArgumentException::class);
        $extension->getElementTypeExtensions('Some\\Element');
    }
}
