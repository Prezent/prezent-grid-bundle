<?php

namespace Prezent\GridBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Sander Marechal
 */
abstract class WebTestCase extends BaseWebTestCase
{
    protected static function createKernel(array $options = []): KernelInterface
    {
        $class = self::getKernelClass();

        if (!isset($options['test_case'])) {
            throw new \InvalidArgumentException('The option "test_case" must be set.');
        }

        return new $class($options['test_case'], 'test', $_ENV['APP_DEBUG']);
    }

    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        $options['test_case'] = substr(strrchr(static::class, '\\'), 1);

        return parent::createClient($options, $server);
    }
}
