<?php

class Calculator {

    private $_pool;
    private $_products;
    private $_calculationStrategy;

    public function __construct($_pool, $_products, $_calculationStrategy) {
        $this->setPool($_pool);
        $this->setProducts($_products);
        $this->setCalculationStrategy($_calculationStrategy);
    }

    public function setPool($_pool) {
        $this->_pool = $_pool;
    }

    public function getPool() {
        return $this->_pool;
    }

    public function setProducts($_products) {
        $this->_products = $_products;
    }

    public function getProducts() {
        return $this->_products;
    }

    public function setCalculationStrategy($_calculationStrategy) {
        $this->_calculationStrategy = $_calculationStrategy;
    }

    public function calculatePrice() {
        return $this->_calculationStrategy->calculatePrice($this->getPool(), $this->getProducts());
    }
}
