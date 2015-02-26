<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 24/02/15
 * Time: 12:06
 */

class Borderstone {

    private $_length;
    private $_width;
    private $_material;
    private $_color;
    private $_tiles;
    private $_category;
    private $_type;

    function __construct($_category, $_color, $_length, $_material, $_tiles, $_type, $_width)
    {
        $this->_category = $_category;
        $this->_color = $_color;
        $this->_length = $_length;
        $this->_material = $_material;
        $this->_tiles = $_tiles;
        $this->_type = $_type;
        $this->_width = $_width;
    }

    public function setCategory($category)
    {
        $this->_category = $category;
    }

    public function getCategory()
    {
        return $this->_category;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function getType()
    {
        return $this->_type;
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

    public function setTiles($tiles)
    {
        $this->_tiles = $tiles;
    }

    public function getTiles()
    {
        return $this->_tiles;
    }

    public function setWidth($width)
    {
        $this->_width = $width;
    }

    public function getWidth()
    {
        return $this->_width;
    }

} 