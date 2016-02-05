<?php

namespace spec\Product;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProductSpec extends ObjectBehavior
{
    private $id = 1;
    private $name = 'Acme Universal Widget';
    private $price = 12.99;

    function it_should_throw_an_exception_with_no_id()
    {
        $id = '';

        $this->beConstructedWith($id, $this->name, $this->price);

        $this->shouldThrow(new \InvalidArgumentException('An ID is required for a product'))->duringInstantiation();
    }

    function it_should_throw_an_exception_if_the_id_is_not_a_positive_int()
    {
        $id = -1;

        $this->beConstructedWith($id, $this->name, $this->price);

        $this->shouldThrow(new \InvalidArgumentException('The product ID must be a positive integer'))
            ->duringInstantiation();
    }

    function it_should_throw_an_exception_with_no_name()
    {
        $name = '';

        $this->beConstructedWith($this->id, $name, $this->price);

        $this->shouldThrow(new \InvalidArgumentException('The product must have a name'))->duringInstantiation();
    }

    function it_should_throw_an_exception_with_no_price()
    {
        $price = '';

        $this->beConstructedWith($this->id, $this->name, $price);

        $this->shouldThrow(new \InvalidArgumentException('The product must have a price'))->duringInstantiation();
    }

    function it_should_throw_an_exception_if_the_price_is_not_a_number()
    {
        $price = 'adf';

        $this->beConstructedWith($this->id, $this->name, $price);

        $this->shouldThrow(new \InvalidArgumentException('The product price must be a number'))->duringInstantiation();
    }
}
