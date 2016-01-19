<?php

namespace spec\Basket;

use PhpSpec\ObjectBehavior;
use Product\Product;
use Prophecy\Argument;

class BasketProductSpec extends ObjectBehavior
{
    function it_should_throw_an_exception_if_the_quantity_is_not_a_positive_int(Product $product)
    {
        $this->beConstructedWith($product, -1);

        $this->shouldThrow(new \InvalidArgumentException('Quantity must be greater than zero'))->duringInstantiation();
    }
}
