<?php 

class Voegsel extends Product{	

    private $_color;
	
	function __construct($_price, $_referenceInternal, $_color=''){
		parent::__construct($_price, $_referenceInternal);
		$this->setColor($color);
	}
	
	public function setColor($color)
    {
        $this->_color = $color;
    }

    public function getColor()
    {
        return $this->_color;
    }

}