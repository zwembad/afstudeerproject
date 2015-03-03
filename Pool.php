<?php
    
class Pool {
    private $_length;
    private $_width;
    private $_depth;
    private $_diameter;
    private $_shape;
    private $_type;
    private $_volume;
    private $_covering;
    private $_buildinPercentage;
    
    function __construct($_depth, $_diameter, $_length, $_shape, $_type, $_width, $_volume, $_covering, $_buildinPercentage)
    {
        $this->_depth = $_depth;
        $this->_diameter = $_diameter;
        $this->_length = $_length;
        $this->_shape = $_shape;
        $this->_type = $_type;
        $this->_width = $_width;
        $this->_volume = $_volume;
        $this->_covering = $_covering;
        $this->_buildinPercentage = $_buildinPercentage;
    }
    /**
     * @param mixed $buildinPercentage
     */
    public function setBuildinPercentage($buildinPercentage)
    {
        $this->_buildinPercentage = $buildinPercentage;
    }
    /**
     * @return mixed
     */
    public function getBuildinPercentage()
    {
        return $this->_buildinPercentage;
    }
    /**
     * @param mixed $covering
     */
    public function setCovering($covering)
    {
        $this->_covering = $covering;
    }
    /**
     * @return mixed
     */
    public function getCovering()
    {
        return $this->_covering;
    }
    /**
     * @param mixed $volume
     */
    public function setVolume($volume)
    {
        $this->_volume = $volume;
    }
    /**
     * @return mixed
     */
    public function getVolume()
    {
        return $this->_volume;
    }
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