<?php

namespace Prezent\GridBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * @author Sander Marechal
 */
abstract class WebTestCase extends BaseWebTestCase
{
    protected static function createKernel(array $options = [])
    {
        $class = self::getKernelClass();

        if (!isset($options['test_case'])) {
            throw new \InvalidArgumentException('The option "test_case" must be set.');
        }

        return new $class($options['test_case'], 'test', true);
    }

    protected static function createClient(array $options = [], array $server = [])
    {
        $options['test_case'] = substr(strrchr(static::class, '\\'), 1);

        return parent::createClient($options, $server);
    }
}
