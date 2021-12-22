<?php

namespace Prezent\GridBundle\Tests\Fixtures;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Sander Marechal
 */
class AppKernel extends Kernel
{
    public function __construct($testCase, $env, $debug)
    {
        $this->testCase = $testCase;

        parent::__construct($env, $debug);
    }

    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Prezent\GridBundle\PrezentGridBundle(),
            new \Prezent\GridBundle\Tests\Fixtures\AppBundle\AppBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');

        if (!method_exists(ContainerBuilder::class, 'registerForAutoconfiguration')) {
            $loader->load(__DIR__.'/config/grids.yml');
        }
    }

    public function getRootDir(): string
    {
        return __DIR__;
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/'.Kernel::VERSION.'/'.$this->testCase.'/cache/'.$this->environment;
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/'.Kernel::VERSION.'/'.$this->testCase.'/logs';
    }
}

if (!class_exists('AppKernel')) {
    class_alias(AppKernel::class, 'AppKernel');
}
