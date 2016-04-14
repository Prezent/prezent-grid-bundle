<?php

namespace Prezent\GridBundle\Grid\Extension;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\GridExtension;

/**
 * Lazy-load grid types and element types
 *
 * @see BaseGridExtension
 * @author Sander Marechal
 */
class GridBundleExtension implements GridExtension
{
    /**
     * @var array
     */
    private $gridTypeIds;

    /**
     * @var array
     */
    private $gridtypeExtensionIds;

    /**
     * @var array
     */
    private $elementTypeIds;

    /**
     * @var array
     */
    private $elementTypeExtensionIds;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param array $gridTypeIds
     * @param array $gridTypeExtensionIds
     * @param array $elementTypeIds
     * @param array $elementTypeExtensionIds
     */
    public function __construct(
        ContainerInterface $container,
        array $gridTypeIds,
        array $gridTypeExtensionIds,
        array $elementTypeIds,
        array $elementTypeExtensionIds
    ) {
        $this->container = $container;
        $this->gridTypeIds = $gridTypeIds;
        $this->gridTypeExtensionIds = $gridTypeExtensionIds;
        $this->elementTypeIds = $elementTypeIds;
        $this->elementTypeExtensionIds = $elementTypeExtensionIds;
    }

    /**
     * {@inheritDoc}
     */
    public function hasGridType($name)
    {
        return isset($this->gridTypeIds[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getGridType($name)
    {
        if (!$this->hasGridType($name)) {
            throw new InvalidArgumentException(sprintf('The grid type "%s" is not registered with the service container.', $name));
        }

        return $this->container->get($this->gridTypeIds[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getGridTypeExtensions($name)
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
    public function hasElementType($name)
    {
        return isset($this->elementTypeIds[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getElementType($name)
    {
        if (!$this->hasElementType($name)) {
            throw new InvalidArgumentException(sprintf('The element type "%s" is not registered with the service container.', $name));
        }

        return $this->container->get($this->elementTypeIds[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getElementTypeExtensions($name)
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
    private function loadExtension($serviceId, $name)
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
