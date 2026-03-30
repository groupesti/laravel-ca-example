<?php

declare(strict_types=1);

namespace CA\Example\Tests;

use CA\Example\ExampleServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    #[\Override]
    protected function getPackageProviders($app): array
    {
        return [
            ExampleServiceProvider::class,
        ];
    }

    #[\Override]
    protected function defineEnvironment($app): void
    {
        $app['config']->set('ca-example.organization', 'Test Corp');
        $app['config']->set('ca-example.domain', 'test.local');
        $app['config']->set('ca-example.demo_prefix', 'TEST-');
    }
}
