<?php

class Quote {

    private $_borderstonesStraight;
    private $_borderstonesInnerCorner;
    private $_borderstonesOuterCornerLeft;
    private $_borderstonesOuterCornerRight;
    private $_borderstonesCurved;
    private $_tiles;
    private $_voegsel;
    private $_transport;

    private $_priceBorderstonesStraight;
    private $_priceBorderstonesInnerCorner;
    private $_priceBorderstonesOuterCornerLeft;
    private $_priceBorderstonesOuterCornerRight;
    private $_priceBorderstonesCurved;
    private $_priceTiles;
    private $_priceVoegsel;
    private $_priceTransport;
    private $_priceTotal;

    function __construct($_borderstonesCurved, $_borderstonesInnerCorner, $_borderstonesOuterCornerLeft, $_borderstonesOuterCornerRight, $_borderstonesStraight, $_priceBorderstonesCurved, $_priceBorderstonesInnerCorner, $_priceBorderstonesOuterCornerLeft, $_priceBorderstonesOuterCornerRight, $_priceBorderstonesStraight, $_priceTiles, $_priceTotal, $_priceTransport, $_priceVoegsel, $_tiles, $_transport, $_voegsel)
    {
        $this->_borderstonesCurved = $_borderstonesCurved;
        $this->_borderstonesInnerCorner = $_borderstonesInnerCorner;
        $this->_borderstonesOuterCornerLeft = $_borderstonesOuterCornerLeft;
        $this->_borderstonesOuterCornerRight = $_borderstonesOuterCornerRight;
        $this->_borderstonesStraight = $_borderstonesStraight;
        $this->_priceBorderstonesCurved = $_priceBorderstonesCurved;
        $this->_priceBorderstonesInnerCorner = $_priceBorderstonesInnerCorner;
        $this->_priceBorderstonesOuterCornerLeft = $_priceBorderstonesOuterCornerLeft;
        $this->_priceBorderstonesOuterCornerRight = $_priceBorderstonesOuterCornerRight;
        $this->_priceBorderstonesStraight = $_priceBorderstonesStraight;
        $this->_priceTiles = $_priceTiles;
        $this->_priceTotal = $_priceTotal;
        $this->_priceTransport = $_priceTransport;
        $this->_priceVoegsel = $_priceVoegsel;
        $this->_tiles = $_tiles;
        $this->_transport = $_transport;
        $this->_voegsel = $_voegsel;
    }


    public function setBorderstonesCurved($borderstonesCurved)
    {
        $this->_borderstonesCurved = $borderstonesCurved;
    }

    public function getBorderstonesCurved()
    {
        return $this->_borderstonesCurved;
    }

    public function setBorderstonesInnerCorner($borderstonesInnerCorner)
    {
        $this->_borderstonesInnerCorner = $borderstonesInnerCorner;
    }

    public function getBorderstonesInnerCorner()
    {
        return $this->_borderstonesInnerCorner;
    }

    public function setBorderstonesOuterCornerLeft($borderstonesOuterCornerLeft)
    {
        $this->_borderstonesOuterCornerLeft = $borderstonesOuterCornerLeft;
    }

    public function getBorderstonesOuterCornerLeft()
    {
        return $this->_borderstonesOuterCornerLeft;
    }

    public function setBorderstonesOuterCornerRight($borderstonesOuterCornerRight)
    {
        $this->_borderstonesOuterCornerRight = $borderstonesOuterCornerRight;
    }

    public function getBorderstonesOuterCornerRight()
    {
        return $this->_borderstonesOuterCornerRight;
    }

    public function setBorderstonesStraight($borderstonesStraight)
    {
        $this->_borderstonesStraight = $borderstonesStraight;
    }

    public function getBorderstonesStraight()
    {
        return $this->_borderstonesStraight;
    }

    public function setPriceBorderstonesCurved($priceBorderstonesCurved)
    {
        $this->_priceBorderstonesCurved = $priceBorderstonesCurved;
    }

    public function getPriceBorderstonesCurved()
    {
        return $this->_priceBorderstonesCurved;
    }

    public function setPriceBorderstonesInnerCorner($priceBorderstonesInnerCorner)
    {
        $this->_priceBorderstonesInnerCorner = $priceBorderstonesInnerCorner;
    }

    public function getPriceBorderstonesInnerCorner()
    {
        return $this->_priceBorderstonesInnerCorner;
    }

    public function setPriceBorderstonesOuterCornerLeft($priceBorderstonesOuterCornerLeft)
    {
        $this->_priceBorderstonesOuterCornerLeft = $priceBorderstonesOuterCornerLeft;
    }

    public function getPriceBorderstonesOuterCornerLeft()
    {
        return $this->_priceBorderstonesOuterCornerLeft;
    }

    public function setPriceBorderstonesOuterCornerRight($priceBorderstonesOuterCornerRight)
    {
        $this->_priceBorderstonesOuterCornerRight = $priceBorderstonesOuterCornerRight;
    }

    public function getPriceBorderstonesOuterCornerRight()
    {
        return $this->_priceBorderstonesOuterCornerRight;
    }

    public function setPriceBorderstonesStraight($priceBorderstonesStraight)
    {
        $this->_priceBorderstonesStraight = $priceBorderstonesStraight;
    }

    public function getPriceBorderstonesStraight()
    {
        return $this->_priceBorderstonesStraight;
    }

    public function setPriceTiles($priceTiles)
    {
        $this->_priceTiles = $priceTiles;
    }

    public function getPriceTiles()
    {
        return $this->_priceTiles;
    }

    public function setPriceTotal($priceTotal)
    {
        $this->_priceTotal = $priceTotal;
    }

    public function getPriceTotal()
    {
        return $this->_priceTotal;
    }

    public function setPriceTransport($priceTransport)
    {
        $this->_priceTransport = $priceTransport;
    }

    public function getPriceTransport()
    {
        return $this->_priceTransport;
    }

    public function setPriceVoegsel($priceVoegsel)
    {
        $this->_priceVoegsel = $priceVoegsel;
    }

    public function getPriceVoegsel()
    {
        return $this->_priceVoegsel;
    }

    public function setTiles($tiles)
    {
        $this->_tiles = $tiles;
    }

    public function getTiles()
    {
        return $this->_tiles;
    }

    public function setTransport($transport)
    {
        $this->_transport = $transport;
    }

    public function getTransport()
    {
        return $this->_transport;
    }

    public function setVoegsel($voegsel)
    {
        $this->_voegsel = $voegsel;
    }

    public function getVoegsel()
    {
        return $this->_voegsel;
    }


} 