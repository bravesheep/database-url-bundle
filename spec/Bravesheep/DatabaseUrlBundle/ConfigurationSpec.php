<?php

namespace spec\Bravesheep\DatabaseUrlBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bravesheep\DatabaseUrlBundle\Configuration');
    }

    function it_should_return_a_treebuilder()
    {
        $this->getConfigTreeBuilder()->shouldHaveType('Symfony\Component\Config\Definition\Builder\TreeBuilder');
    }
}
