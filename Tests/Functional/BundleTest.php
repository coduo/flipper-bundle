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

        $flipper = $kernel->getContainer()->get('coduo.flipper');
        $repo = $kernel->getContainer()->get('coduo.flipper.repository');
        $this->assertInstanceOf("Coduo\\Flipper", $flipper);
        $this->assertInstanceOf("Coduo\\Flipper\\Feature\\Repository", $repo);
    }

    /**
     * @test
     */
    public function it_should_be_able_to_check_defined_features()
    {
        $kernel = new TestKernel();
        $kernel->boot();

        $flipper = $kernel->getContainer()->get('coduo.flipper');
        $this->assertTrue($flipper->isActive('captcha'));

    }
}
