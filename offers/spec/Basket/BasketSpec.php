<?php

namespace spec\Basket;

use Basket\BasketProduct;
use Offer\OfferInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BasketSpec extends ObjectBehavior
{
    function it_should_be_possible_to_have_neither_products_nor_offers()
    {
        $productList = [];
        $offerList = [];

        $this->beConstructedWith($productList, $offerList);

        $this->shouldNotThrow('\InvalidArgumentException')->duringInstantiation();
    }

    function it_should_throw_an_exception_if_a_product_has_the_incorrect_type(BasketProduct $product)
    {
        $this->beConstructedWith([$product, 'not a basket product']);

        $this->shouldThrow(new \InvalidArgumentException('Incorrect products added'))->duringInstantiation();
    }

    function it_should_throw_an_exception_if_an_offer_has_the_incorrect_type(OfferInterface $offer)
    {
        $this->beConstructedWith([], [$offer, 'not an offer']);

        $this->shouldThrow(new \InvalidArgumentException('Incorrect offer added'))->duringInstantiation();
    }
}
