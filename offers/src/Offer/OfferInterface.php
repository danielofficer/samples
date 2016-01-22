<?php

namespace Offer;

use Basket\BasketProduct;

interface OfferInterface
{
    /**
     * @param BasketProduct[] $basketProductList
     *
     * @return BasketProduct[]
     */
    public function applyOffer(array $basketProductList);
    /**
     * @return string
     */
    public function getOfferName();

    /**
     * @return int
     */
    public function getOfferId();
}
