<?php

namespace Basket;

use Product\ProductInterface;

class BasketProduct
{
    /**
     * @var ProductInterface
     */
    private $product;
    /**
     * @var int
     */
    private $quantity;

    public function __construct(ProductInterface $product, $quantity)
    {
        if (!is_int($quantity) || $quantity < 1) {
            throw new \InvalidArgumentException('Quantity must be greater than zero');
        }

        $this->product = $product;
        $this->quantity = $quantity;
    }
}
