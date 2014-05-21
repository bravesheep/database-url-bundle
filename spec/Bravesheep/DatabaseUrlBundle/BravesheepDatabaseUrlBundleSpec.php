<?php

namespace spec\Bravesheep\DatabaseUrlBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BravesheepDatabaseUrlBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bravesheep\DatabaseUrlBundle\BravesheepDatabaseUrlBundle');
    }

    function it_should_return_the_correct_extension()
    {
        $this->getContainerExtension()->shouldHaveType('Bravesheep\\DatabaseUrlBundle\\BravesheepDatabaseUrlExtension');
    }
}
