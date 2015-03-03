<?php

require_once('Product.php');

class Heatpump extends Product {
    
    private $_cop26c;
    private $_cop5c;
    private $_period; // 1 = januari - december; 2 = maart - november; 3 = April - Oktober; 4 = Mei - September;
    private $_desiredTemperature;
    private $_surfacePoolHeatingPercentage;
	
    public function __Construct($_price, $_referenceInternal, $_cop26c, $_cop5c, $_period, $_desiredTemperature, $_surfacePoolHeatingPercentage){
	parent::__construct($_price, $_referenceInternal);
        $this->_cop26c = $_cop26c;
        $this->_cop5c = $_cop5c;
        $this->_period = $_period;
        $this->_desiredTemperature = $_desiredTemperature;
        $this->_surfacePoolHeatingPercentage = $_surfacePoolHeatingPercentage;
    }
    
    public function setCop26c($cop26c) {
        $this->_cop26c = $cop26c;
    }
    
    public function getCop26c() {
        return $this->_cop26c;
    }
    
    public function setCop5c($cop5c) {
        $this->_cop5c = $cop5c;
    }
    
    public function getCop5c() {
        return $this->_cop5c;
    }
    
    /**
     * @param type $period
     * 1 = januari - december;
     * 2 = maart - november;
     * 3 = April - Oktober;
     * 4 = Mei - September;
     */
    public function setPeriod($period) {
        $this->_period = $period;
    }
    
    public function getPeriod() {
        return $this->_period;
    }
    
    public function setDesiredTemperature($temperature) {
        $this->_desiredTemperature = $temperature;
    }
    
    public function getDesiredTemperature() {
        return $this->_desiredTemperature;
    }
    
    public function setSurfacePoolHeatingPercentage($surfacePoolHeatingPercentage) {
        $this->_surfacePoolHeatingPercentage = $surfacePoolHeatingPercentage;
    }
    
    public function getSurfacePoolHeatingPercentage() {
        return $this->_surfacePoolHeatingPercentage;
    }
}