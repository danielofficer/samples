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

    function it_should_return_the_product_price_if_there_is_no_discount_set(Product $product)
    {
        $product->getPrice()->willReturn(9.99);

        $this->beConstructedWith($product, 1);

        $this->getUnitPrice()->shouldReturn(9.99);
    }

    function it_should_return_the_discounted_price_if_there_is_a_discount_set(Product $product)
    {
        $product->getPrice()->willReturn(9.99);

        $this->beConstructedWith($product, 1);
        $this->setDiscountedPrice(6.99);

        $this->getUnitPrice()->shouldReturn(6.99);
    }

    function it_should_return_zero_if_the_discounted_price_is_set_to_zero(Product $product)
    {
        $product->getPrice()->willReturn(9.99);

        $this->beConstructedWith($product, 1);
        $this->setDiscountedPrice(0);

        $this->getUnitPrice()->shouldReturn(0);

    }

    function it_should_return_the_total_price_as_unit_price_times_the_quantity(Product $product)
    {
        $product->getPrice()->willReturn(10.00);

        $this->beConstructedWith($product, 2);

        $this->getTotalPrice()->shouldReturn(20.00);
    }
}
