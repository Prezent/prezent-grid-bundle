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
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var VariableResolver
     */
    private $resolver;

    /**
     * Constructor
     *
     * @param UrlGeneratorInterface $router
     * @param VariableResolver $resolver
     */
    public function __construct(UrlGeneratorInterface $router, VariableResolver $resolver)
    {
        $this->router = $router;
        $this->resolver = $resolver;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadColumnTypeExtensions()
    {
        return [
            new Type\RouteTypeExtension($this->router, $this->resolver),
            new Type\TranslatableLabelTypeExtension(),
        ];
    }
}
