<?php

namespace Basket;

use Offer\OfferInterface;

class Basket
{
    /**
     * @var BasketProduct[]
     */
    private $productList;
    /**
     * @var OfferInterface[]
     */
    private $offerList;

    public function __construct(array $productList = [], array $offerList = [])
    {
        $this->validateConstructorParameters($productList, $offerList);
        $this->productList = $productList;
        $this->offerList = $offerList;
    }

    /**
     * @param array $productList
     * @param array $offerList
     */
    private function validateConstructorParameters(array $productList, array $offerList)
    {
        foreach ($productList as $product) {
            if (!$product instanceof BasketProduct) {
                throw new \InvalidArgumentException('Incorrect products added');
            }
        }

        foreach ($offerList as $offer) {
            if (!$offer instanceof OfferInterface) {
                throw new \InvalidArgumentException('Incorrect offer added');
            }
        }
    }
}
