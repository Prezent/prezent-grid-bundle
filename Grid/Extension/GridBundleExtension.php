<?php

namespace Prezent\GridBundle\Grid\Extension;

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\VariableResolver;
use Prezent\GridBundle\Grid\Type;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Symfony type extensions
 *
 * @see BaseGridExtension
 * @author Sander Marechal
 */
class GridBundleExtension extends BaseGridExtension
{
    /**
     * @var array
     */
    private $gridTypes = [];

    /**
     * @var array
     */
    private $gridTypeExtensions = [];

    /**
     * @var array
     */
    private $elementTypes = [];

    /**
     * @var array
     */
    private $elementTypeExtensions = [];

    /**
     * Constructor
     *
     * @param array $gridTypes
     * @param array $gridTypeExtensions
     * @param array $elementTypes
     * @param array $elementTypeExtensions
     */
    public function __construct(
        array $gridTypes,
        array $gridTypeExtensions,
        array $elementTypes,
        array $elementTypeExtensions
    ) {
        $this->gridTypes = $gridTypes;
        $this->gridTypeExtensions = $gridTypeExtensions;
        $this->elementTypes = $elementTypes;
        $this->elementTypeExtensions = $elementTypeExtensions;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadGridTypes()
    {
        return $this->gridTypes;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadGridTypeExtensions()
    {
        return $this->gridTypeExtensions;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadElementTypes()
    {
        return $this->elementTypes;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadElementTypeExtensions()
    {
        return $this->elementTypeExtensions;
    }
}
