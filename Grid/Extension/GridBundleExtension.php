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
    private $types;

    /**
     * @var array
     */
    private $extensions;

    /**
     * Constructor
     *
     * @param UrlGeneratorInterface $router
     * @param VariableResolver $resolver
     */
    public function __construct(array $types, array $extensions)
    {
        $this->types = $types;
        $this->extensions = $extensions;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadColumnTypes()
    {
        return $this->types;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadColumnTypeExtensions()
    {
        return $this->extensions;
    }
}
