<?php

namespace Basket;

use Offer\OfferInterface;
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
    /**
     * @var OfferInterface
     */
    private $activeOffer;
    /**
     * @var float
     */
    private $discountedPrice;

    public function __construct(ProductInterface $product, $quantity)
    {
        if (!is_int($quantity) || $quantity < 1) {
            throw new \InvalidArgumentException('Quantity must be greater than zero');
        }

        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return OfferInterface
     */
    public function getActiveOffer()
    {
        return $this->activeOffer;
    }

    /**
     * @param OfferInterface $activeOffer
     */
    public function setActiveOffer($activeOffer)
    {
        $this->activeOffer = $activeOffer;
    }

    /**
     * @return float
     */
    public function getUnitPrice()
    {
        return isset($this->discountedPrice) ? $this->discountedPrice : $this->getProduct()->getPrice();
    }

    /**
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->getUnitPrice() * $this->getQuantity();
    }

    /**
     * @param float $discountedPrice
     */
    public function setDiscountedPrice($discountedPrice)
    {
        $this->discountedPrice = $discountedPrice;
    }

    /**
     * @return float
     */
    public function getDiscountedPrice()
    {
        return $this->discountedPrice;
    }
}
