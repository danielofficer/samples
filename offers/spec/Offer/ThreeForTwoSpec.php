<?php

namespace spec\Offer;

use Basket\BasketProduct;
use Basket\BasketProductSplitter;
use PhpSpec\ObjectBehavior;
use Product\Product;
use Prophecy\Argument;

class ThreeForTwoSpec extends ObjectBehavior
{
    private $offerId = 1;
    private $offerName = 'Three for Two Offer';

    function it_should_throw_an_exception_if_the_offer_has_no_name()
    {
        $this->beConstructedWith($this->offerId, '');

        $this->shouldThrow(new \InvalidArgumentException('Offer must have a name'))->duringInstantiation();
    }

    function it_should_throw_an_exception_if_the_offer_has_no_id()
    {
        $this->beConstructedWith('', $this->offerName);

        $this->shouldThrow(new \InvalidArgumentException('Offer must have an ID'))->duringInstantiation();
    }

    function it_should_do_nothing_if_there_are_fewer_than_three_products()
    {
        $this->beConstructedWith($this->offerId, $this->offerName);

        $bp1 = new BasketProduct(new Product(1, 'aaa', 1.00), 1);
        $bp2 = new BasketProduct(new Product(2, 'bbb', 1.00), 1);

        $this->applyOffer([$bp1, $bp2])->shouldReturn([$bp1, $bp2]);
    }

    function it_should_apply_the_offer_to_all_products_if_there_are_three_products()
    {
        $this->beConstructedWith($this->offerId, $this->offerName);
        $bp1 = new BasketProduct(new Product(1, 'aaa', 1.00), 1);
        $bp2 = new BasketProduct(new Product(2, 'bbb', 1.00), 1);
        $bp3 = new BasketProduct(new Product(3, 'ccc', 1.00), 1);

        $prods = $this->applyOffer([$bp1, $bp2, $bp3]);

        $prods->shouldHaveCount(3);
        $prods[0]->getActiveOffer()->getOfferId()->shouldReturn($this->offerId);
        $prods[1]->getActiveOffer()->getOfferId()->shouldReturn($this->offerId);
        $prods[2]->getActiveOffer()->getOfferId()->shouldReturn($this->offerId);
    }

    function it_should_apply_the_offer_to_the_cheapest_three_products_if_there_are_more_than_three_products()
    {
        $this->beConstructedWith($this->offerId, $this->offerName);
        $bp1 = new BasketProduct(new Product(1, 'aaa', 4.00), 1);
        $bp2 = new BasketProduct(new Product(2, 'bbb', 3.00), 1);
        $bp3 = new BasketProduct(new Product(3, 'ccc', 2.00), 1);
        $bp4 = new BasketProduct(new Product(4, 'ddd', 1.00), 1);

        $prods = $this->applyOffer([$bp1, $bp2, $bp3, $bp4]);

        $prods->shouldHaveCount(4);
        $prods[0]->getActiveOffer()->getOfferId()->shouldReturn($this->offerId);
        $prods[1]->getActiveOffer()->getOfferId()->shouldReturn($this->offerId);
        $prods[2]->getActiveOffer()->getOfferId()->shouldReturn($this->offerId);
        $prods[3]->getActiveOffer()->shouldReturn(null);
    }

    function it_should_set_the_discounted_price_to_zero_for_the_lowest_valued_item_in_the_offer()
    {
        $this->beConstructedWith($this->offerId, $this->offerName);
        $bp1 = new BasketProduct(new Product(1, 'aaa', 4.00), 1);
        $bp2 = new BasketProduct(new Product(2, 'bbb', 3.00), 1);
        $bp3 = new BasketProduct(new Product(3, 'ccc', 2.00), 1);
        $bp4 = new BasketProduct(new Product(4, 'ddd', 1.00), 1);

        $prods = $this->applyOffer([$bp1, $bp2, $bp3, $bp4]);

        $prods[0]->getDiscountedPrice()->shouldReturn(0);
        $prods[0]->getProduct()->getId()->shouldReturn(4);
    }

    function it_should_split_the_products_if_the_cheapest_one_has_quantity_more_than_one()
    {
        $this->beConstructedWith($this->offerId, $this->offerName);
        $bp1 = new BasketProduct(new Product(1, 'aaa', 4.00), 1);
        $bp2 = new BasketProduct(new Product(2, 'bbb', 3.00), 2);

        $prods = $this->applyOffer([$bp1, $bp2]);

        $prods->shouldHaveCount(3);
        $prods[0]->getProduct()->getId()->shouldReturn(2);
        $prods[0]->getQuantity()->shouldReturn(1);
        $prods[0]->getUnitPrice()->shouldReturn(0);
    }

    function it_should_split_the_second_product_if_one_is_in_an_offer_and_the_other_is_not()
    {
        $this->beConstructedWith($this->offerId, $this->offerName);
        $bp1 = new BasketProduct(new Product(1, 'aaa', 4.00), 2);
        $bp2 = new BasketProduct(new Product(2, 'bbb', 3.00), 2);

        $prods = $this->applyOffer([$bp1, $bp2]);

        $prods->shouldHaveCount(4);
        $prods[3]->getProduct()->getId()->shouldReturn(1);
        $prods[3]->getQuantity()->shouldReturn(1);
        $prods[3]->getActiveOffer()->shouldReturn(null);
    }

}
