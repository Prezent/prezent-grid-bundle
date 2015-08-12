<?php

namespace Prezent\GridBundle\Grid\Extension;

use Prezent\Grid\BaseGridExtension;
use Prezent\GridBundle\Grid\Type;

/**
 * Symfony type extensions
 *
 * @see BaseGridExtension
 * @author Sander Marechal
 */
class GridBundleExtension extends BaseGridExtension
{
    /**
     * {@inheritDoc}
     */
    protected function loadColumnTypeExtensions()
    {
        return [
            new Type\TranslatableLabelTypeExtension(),
        ];
    }
}
