<?php

namespace spec\Offer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ThreeForTwoSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Offer\ThreeForTwo');
    }
}
