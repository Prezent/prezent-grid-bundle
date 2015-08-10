<?php

namespace Prezent\GridBundle;

use Prezent\GridBundle\DependencyInjection\Compiler\GridCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Grid bundle
 *
 * @see Bundle
 * @author Sander Marechal
 */
class PrezentGridBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new GridCompilerPass());
    }
}
