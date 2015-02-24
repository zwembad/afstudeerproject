<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 24/02/15
 * Time: 12:06
 */

class Pool {

    private $_length;
    private $_width;
    private $_depth;
    private $_diameter;
    private $_shape;
    private $_type;

    /**
     * @param mixed $depth
     */
    public function setDepth($depth)
    {
        $this->_depth = $depth;
    }

    /**
     * @return mixed
     */
    public function getDepth()
    {
        return $this->_depth;
    }

    /**
     * @param mixed $diameter
     */
    public function setDiameter($diameter)
    {
        $this->_diameter = $diameter;
    }

    /**
     * @return mixed
     */
    public function getDiameter()
    {
        return $this->_diameter;
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
     * @param mixed $shape
     */
    public function setShape($shape)
    {
        $this->_shape = $shape;
    }

    /**
     * @return mixed
     */
    public function getShape()
    {
        return $this->_shape;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
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