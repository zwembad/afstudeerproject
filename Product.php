<?php

abstract class Product {

    protected  $_price;
    protected  $_referenceInternal;

    function __construct($_price, $_referenceInternal)
    {
        $this->_price = $_price;
        $this->_referenceInternal = $_referenceInternal;
    }

    public function setPrice($price)
    {
        $this->_price = $price;
    }

    public function getPrice()
    {
        return $this->_price;
    }

    public function setReferenceInternal($referenceInternal)
    {
        $this->_referenceInternal = $referenceInternal;
    }

    public function getReferenceInternal()
    {
        return $this->_referenceInternal;
    }
}