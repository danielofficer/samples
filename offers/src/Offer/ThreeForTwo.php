<?php

namespace Offer;

use Basket\BasketProduct;

class ThreeForTwo implements OfferInterface
{
    const PRODUCTS_REQUIRED_FOR_OFFER = 3;

    /**
     * @var int
     */
    private $offerId;
    /**
     * @var string
     */
    private $offerName;

    public function __construct($offerId, $offerName)
    {
        if (empty($offerId)) {
            throw new \InvalidArgumentException('Offer must have an ID');
        }
        if (empty($offerName)) {
            throw new \InvalidArgumentException('Offer must have a name');
        }

        $this->offerName = $offerName;
        $this->offerId = $offerId;
    }

    /**
     * @param BasketProduct[] $basketProductList
     * @return BasketProduct[]
     */
    public function applyOffer(array $basketProductList)
    {
        $totalProducts = array_sum(array_map(
                function(BasketProduct $product) {
                    return $product->getQuantity();
                }
                , $basketProductList
            )
        );

        if ($totalProducts >= self::PRODUCTS_REQUIRED_FOR_OFFER) {
            $basketProductList = $this->sortProductsByPrice($basketProductList);

            $basketProductList = $this->splitAllProducts($basketProductList);

            $basketProductList = $this->addOfferToProducts($basketProductList);

            $basketProductList = $this->mergeAllProducts($basketProductList);
        }

        return $basketProductList;
    }

    /**
     * @return BasketProduct[]
     */
    private function sortProductsByPrice($sortList)
    {
        # Squashing error output here since usort gives a warning when used with PHPSpec
        # Apparently fixed in PHP7
        @usort($sortList, function ($a, $b) {
            return $a->getProduct()->getPrice() >= $b->getProduct()->getPrice() ? 1 : -1;
        });
        return $sortList;
    }

    /**
     * @param BasketProduct[] $basketProductList
     */
    private function addOfferToProducts(array $basketProductList)
    {
        $productCount = 0;
        foreach ($basketProductList as $basketProduct) {
            if (0 == $productCount) {
                $basketProduct->setDiscountedPrice(0);
            }
            if ($productCount >= self::PRODUCTS_REQUIRED_FOR_OFFER) {
                continue;
            }
            $basketProduct->setActiveOffer(new ThreeForTwo($this->getOfferId(), $this->getOfferName()));
            $productCount++;
        }

        return $basketProductList;
    }

    /**
     * @param BasketProduct[] $basketProductList
     * @return BasketProduct[]
     */
    private function splitAllProducts(array $basketProductList)
    {
        $newProductList = [];

        foreach ($basketProductList as $basketProduct) {
            for ($i = 0; $i < $basketProduct->getQuantity(); $i++) {
                $newBasketProduct = new BasketProduct($basketProduct->getProduct(), 1);
                $newBasketProduct->setDiscountedPrice($basketProduct->getDiscountedPrice());
                $newBasketProduct->setActiveOffer($basketProduct->getActiveOffer());

                $newProductList[] = $newBasketProduct;
            }
        }


        return $newProductList;
    }

    /**
     * @param BasketProduct[] $basketProductList
     * @return BasketProduct[]
     */
    private function mergeAllProducts(array $basketProductList)
    {
        /**
         * @var BasketProduct[] $newProductList
         */
        $newProductList = [];

        $prevMergeKey = '';
        foreach ($basketProductList as $basketProduct) {
            $mergeKey = $this->buildMergeKey($basketProduct);
            if ($prevMergeKey == $mergeKey) {
                $newProductList[$mergeKey]->setQuantity($newProductList[$mergeKey]->getQuantity() + $basketProduct->getQuantity());
            } else {
                $newProductList[$mergeKey] = new BasketProduct($basketProduct->getProduct(), $basketProduct->getQuantity());
                $newProductList[$mergeKey]->setActiveOffer($basketProduct->getActiveOffer());
                $newProductList[$mergeKey]->setDiscountedPrice($basketProduct->getDiscountedPrice());
            }

            $prevMergeKey = $mergeKey;
        }
        return array_values($newProductList);
    }

    /**
     * @return string
     */
    public function getOfferName()
    {
        return $this->offerName;
    }

    /**
     * @return int
     */
    public function getOfferId()
    {
        return $this->offerId;
    }

    /**
     * @param BasketProduct $basketProduct
     * @return string
     */
    private function buildMergeKey($basketProduct)
    {
        $key = $basketProduct->getProduct()->getId() . '-';
        $key .= $basketProduct->getUnitPrice();
        $key .= $basketProduct->getActiveOffer()
            ? '-' . $basketProduct->getActiveOffer()->getOfferId()
            : '-0';
        return $key;
    }
}
