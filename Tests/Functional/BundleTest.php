<?php

namespace Coduo\FlipperBundle\Tests\Functional;

use Coduo\FlipperBundle\Tests\Functional\Fixtures\TestKernel;

class BundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_register_correctly()
    {
        $kernel = new TestKernel();
        $kernel->boot();
    }
}
