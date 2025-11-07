<?php

declare(strict_types=1);

namespace Prezent\GridBundle\Grid\Extension;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\GridExtension;
use Psr\Container\ContainerInterface;

/**
 * Lazy-load grid types and element types
 *
 * @see BaseGridExtension
 * @author Sander Marechal
 */
class GridBundleExtension implements GridExtension
{
    private array $gridTypeIds;

    private array $gridTypeExtensionIds;

    private array $elementTypeIds;

    private array $elementTypeExtensionIds;

    public function __construct(
        private readonly ContainerInterface $container,
        array $gridTypeIds,
        array $gridTypeExtensionIds,
        array $elementTypeIds,
        array $elementTypeExtensionIds
    ) {
        $this->gridTypeIds = $gridTypeIds;
        $this->gridTypeExtensionIds = $gridTypeExtensionIds;
        $this->elementTypeIds = $elementTypeIds;
        $this->elementTypeExtensionIds = $elementTypeExtensionIds;
    }

    /**
     * {@inheritDoc}
     */
    public function hasGridType(string $name): bool
    {
        return isset($this->gridTypeIds[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getGridType(string $name): mixed
    {
        if (!$this->hasGridType($name)) {
            throw new InvalidArgumentException(sprintf('The grid type "%s" is not registered with the service container.', $name));
        }

        return $this->container->get($this->gridTypeIds[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getGridTypeExtensions(string $name): array
    {
        $extensions = [];

        if (isset($this->gridTypeExtensionIds[$name])) {
            foreach ($this->gridTypeExtensionIds[$name] as $serviceId) {
                $extensions[] = $this->loadExtension($serviceId, $name);
            }
        }

        return $extensions;
    }

    /**
     * {@inheritDoc}
     */
    public function hasElementType(string $name): bool
    {
        return isset($this->elementTypeIds[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getElementType(string $name): mixed
    {
        if (!$this->hasElementType($name)) {
            throw new InvalidArgumentException(sprintf('The element type "%s" is not registered with the service container.', $name));
        }

        return $this->container->get($this->elementTypeIds[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getElementTypeExtensions(string $name): array
    {
        $extensions = [];

        if (isset($this->elementTypeExtensionIds[$name])) {
            foreach ($this->elementTypeExtensionIds[$name] as $serviceId) {
                $extensions[] = $this->loadExtension($serviceId, $name);
            }
        }

        return $extensions;
    }

    /**
     * Load a type extension
     *
     * @param string $serviceId
     * @param string $name
     * @return mixed
     */
    private function loadExtension(string $serviceId, string $name): mixed
    {
        $extension = $this->container->get($serviceId);

        if ($extension->getExtendedType() !== $name) {
            throw new InvalidArgumentException(sprintf(
                'The extended type specified for the service "%s" does not match the actual extended type. Expected "%s", given "%s".',
                $serviceId,
                $name,
                $extension->getExtendedType()
            ));
        }

        return $extension;
    }
}
