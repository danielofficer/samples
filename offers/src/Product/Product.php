<?php

namespace Product;

class Product implements ProductInterface
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var float
     */
    private $price;

    /**
     * @param int    $id
     * @param string $name
     * @param float  $price
     */
    public function __construct($id, $name, $price)
    {
        $this->validateConstructorParameters($id, $name, $price);
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return (float) $this->price;
    }

    /**
     * @param $id
     * @param $name
     * @param $price
     */
    private function validateConstructorParameters($id, $name, $price)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('An ID is required for a product');
        }

        if (!is_int($id) || $id < 1) {
            throw new \InvalidArgumentException('The product ID must be a positive integer');
        }

        if (empty($name)) {
            throw new \InvalidArgumentException('The product must have a name');
        }

        if (empty($price)) {
            throw new \InvalidArgumentException('The product must have a price');
        }

        if (!is_numeric($price)) {
            throw new \InvalidArgumentException('The product price must be a number');
        }
    }
}
