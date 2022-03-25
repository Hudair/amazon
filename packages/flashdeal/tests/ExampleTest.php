<?php

namespace Incevio\Package\Inspector\Tests;

use Orchestra\Testbench\TestCase;
use Incevio\Package\Inspector\FlashdealServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [FlashdealServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
