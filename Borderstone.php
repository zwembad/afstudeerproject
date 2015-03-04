<?php

class Borderstone extends Product {

    private $_length;
    private $_width;
    private $_height;
    private $_category; //natuursteen or betonsteen
    private $_material; //jasberg, black panda, ...
    private $_color;
    private $_shape; // gebogen, binnenhoek, buitenhoek, ...

    function __construct($_price, $_referenceInternal, $_category, $_color, $_height, $_length, $_material, $_width, $_shape)
    {
		parent::__construct($_price, $_referenceInternal);
        $this->_category = $_category;
        $this->_color = $_color;
		$this->_height = $_height;
        $this->_length = $_length;
        $this->_material = $_material;
        $this->_width = $_width;
		$this->_shape = $_shape;
    }

    public function setCategory($category)
    {
        $this->_category = $category;
    }

    public function getCategory()
    {
        return $this->_category;
    }

    public function setColor($color)
    {
        $this->_color = $color;
    }

    public function getColor()
    {
        return $this->_color;
    }

    public function setLength($length)
    {
        $this->_length = $length;
    }

    public function getLength()
    {
        return $this->_length;
    }

    public function setMaterial($material)
    {
        $this->_material = $material;
    }

    public function getMaterial()
    {
        return $this->_material;
    }

    public function setWidth($width)
    {
        $this->_width = $width;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function setHeight($height)
    {
        $this->_height = $height;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setShape($shape)
    {
        $this->_shape = $shape;
    }

    public function getShape()
    {
        return $this->_shape;
    }

} 