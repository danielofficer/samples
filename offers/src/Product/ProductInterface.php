<?php

namespace Product;

interface ProductInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return float
     */
    public function getPrice();
}
