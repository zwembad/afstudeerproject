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

    function __construct($_color, $_length, $_material, $_tiles, $_width)
    {
        $this->_color = $_color;
        $this->_length = $_length;
        $this->_material = $_material;
        $this->_tiles = $_tiles;
        $this->_width = $_width;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->_color = $color;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->_color;
    }

    /**
     * @param mixed $length
     */
    public function setLength($length)
    {
        $this->_length = $length;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->_length;
    }

    /**
     * @param mixed $material
     */
    public function setMaterial($material)
    {
        $this->_material = $material;
    }

    /**
     * @return mixed
     */
    public function getMaterial()
    {
        return $this->_material;
    }

    /**
     * @param mixed $tiles
     */
    public function setTiles($tiles)
    {
        $this->_tiles = $tiles;
    }

    /**
     * @return mixed
     */
    public function getTiles()
    {
        return $this->_tiles;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->_width = $width;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->_width;
    }

} 