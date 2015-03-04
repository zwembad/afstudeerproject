<?php

require_once('CalculationStrategy.php');
require_once('Pool.php');
require_once('Heatpump.php');

class HeatpumpCalculationStrategy implements CalculationStrategy {
    
    private static $GEM_TEMP_ARRAY = array(2.5,3.2,5.7,8.7,12.7,15.7,17.2,17,14.4,10.4,6,3.4);
    private static $GEM_MAX_TEMP_AYYAY = array(5.1,6.1,9.1,12.7,16.2,19.8,21.8,21.9,18.9,14.4,9.2,6);
    private static $TEMP_ZONDER_VERWARMING_Z_ARRAY = array(5,6,9,12,16,20,22,22,18,15,9,6);
    private static $TEMP_ZONDER_VERWARMING_AA_ARRAY = array(0,0,0,1.5,3,5,5,5,3,1.5,0,0);
    
    function __construct() {}
    
    public function calculatePrice($pool, $heatpump) {
        $TEMP_ZONDER_VERWARMING = array();
        $TEMP_VERHOGING = array();
        $KW_TEMP_HOOG = array();
        $GEM_COP = array();
        $KW_POMP = array();
        $KW_VERWERKING = array();
        $VERBRUIK_OLIE = array();
       
        for($month = 0; $month < 12; $month++) {
            $TEMP_ZONDER_VERWARMING[$month] = 
                    self::$TEMP_ZONDER_VERWARMING_Z_ARRAY[$month] + (
                    self::$TEMP_ZONDER_VERWARMING_AA_ARRAY[$month] *
                    $heatpump->getSurfacePoolHeatingPercentage() / 100);
            $TEMP_VERHOGING[$month] = $heatpump->getDesiredTemperature() - $TEMP_ZONDER_VERWARMING[$month];
            $KW_TEMP_HOOG[$month] = (-0.009 * $pool->getBuildinPercentage() + 2) *
                    ($pool->getVolume() / 50) * 11.66 * $TEMP_VERHOGING[$month];
            if ($pool->getCovering() == "neen") {
                $KW_TEMP_HOOG[$month] = $KW_TEMP_HOOG[$month] * 0.7;
            }
            $GEM_COP[$month] = $heatpump->getCop26c() - (26 - self::$GEM_TEMP_ARRAY[$month]) *
                    ($heatpump->getCop26c() - $heatpump->getCop5c()) / 21;
            $KW_POMP[$month] = $KW_TEMP_HOOG[$month] / $GEM_COP[$month];
            $KW_VERWERKING[$month] = $KW_TEMP_HOOG[$month];
            $VERBRUIK_OLIE[$month] = $KW_VERWERKING[$month] / 10;
        }
        
        $KW_POMP_BINNEN = array();
        $TEMP_VERHOGING_BINNEN = $heatpump->getDesiredTemperature() - 17;
        $KW_TEMP_HOOG_BINNEN = (-0.009 * $pool->getBuildinPercentage() + 2) *
                ($pool->getVolume() / 50) * 11.66 * $TEMP_VERHOGING_BINNEN;
        $KW_VERWERKING_BINNEN = $KW_TEMP_HOOG_BINNEN;
        $VERBRUIK_OLIE_BINNEN = $KW_VERWERKING_BINNEN / 10;
        for($month = 0; $month < 12; $month++) {
            $KW_POMP_BINNEN[$month] = $KW_TEMP_HOOG_BINNEN / $GEM_COP[$month];
        }

        $kw = 0;
        $factor = 1; //Wordt gebruikt om warmteverlies te berekenen.
        If ($pool->getCovering() == "overkapping" ||
            $pool->getCovering() == "rolldeck" ||
            $pool->getCovering() == "rolldeck solar" ||
            $pool->getCovering() == "binnenbad") {
            $factor = 1.5;
        }
        
        //Q50 is $factor
        //Oude berekening
        /*
        If ($heatpump->getPeriod() == 1) { // 1 = Januari - December;
            // VLOOKUP(30;AC6:AI85;6;TRUE)/Q50
            If ($pool->getCovering() == "binnenbad") {
                $kw = $KW_TEMP_HOOG_BINNEN / ($GEM_COP[1] * 7.5) / $factor;
            } Else {
                $kw = $KW_TEMP_HOOG[0] / ($GEM_COP[1] * 7.5) / $factor;
            }
        } If ($heatpump->getPeriod() == 2) { // 2 = Maart - November;
            // VLOOKUP(30;AE6:AI85;4;TRUE)/Q50
            If ($pool->getCovering() == "binnenbad") {
                $kw = $KW_TEMP_HOOG_BINNEN / ($GEM_COP[1] * 7.5) / $factor;
            } Else {
                $kw = $KW_TEMP_HOOG[2] / ($GEM_COP[3] * 7.5) / $factor;
            }
        } If ($heatpump->getPeriod() == 3) { // 3 = April - Oktober;
            // VLOOKUP(30;AF6:AI85;3;TRUE)/Q50
            If ($pool->getCovering() == "binnenbad") {
                $kw = $KW_TEMP_HOOG_BINNEN / ($GEM_COP[1] * 7.5) / $factor;
            } Else {
                $kw = $KW_TEMP_HOOG[3] / ($GEM_COP[4] * 7.5) / $factor;
            }
        } If ($heatpump->getPeriod() == 4) { // 4 = Mei - September;
            // VLOOKUP (30;AG6:AI85;2;TRUE)/Q50
            If ($pool->getCovering() == "binnenbad") {
                $kw = $KW_TEMP_HOOG_BINNEN / ($GEM_COP[1] * 7.5) / $factor;
            } Else {
                $kw = $KW_TEMP_HOOG[4] / ($GEM_COP[5] * 7.5) / $factor;
            }
        } Else { //Mag nooit gebeuren
            //Geef uw zwemperiode op.
        }*/

        //Q50 is $factor
        //Nieuwe berekening
        If ($heatpump->getPeriod() == 1) { // 1 = Januari - December;
            // VLOOKUP(30;AC6:AI85;6;TRUE)/Q50
            If ($pool->getCovering() == "binnenbad") {
                $kw = $KW_TEMP_HOOG_BINNEN / ($GEM_COP[0] * 7.5) / $factor;
            } Else {
                $kw = $KW_TEMP_HOOG[0] / ($GEM_COP[0] * 7.5) / $factor;
            }
        } If ($heatpump->getPeriod() == 2) { // 2 = Maart - November;
            // VLOOKUP(30;AE6:AI85;4;TRUE)/Q50
            If ($pool->getCovering() == "binnenbad") {
                $kw = $KW_TEMP_HOOG_BINNEN / ($GEM_COP[0] * 7.5) / $factor;
            } Else {
                $kw = $KW_TEMP_HOOG[2] / ($GEM_COP[2] * 7.5) / $factor;
            }
        } If ($heatpump->getPeriod() == 3) { // 3 = April - Oktober;
            // VLOOKUP(30;AF6:AI85;3;TRUE)/Q50
            If ($pool->getCovering() == "binnenbad") {
                $kw = $KW_TEMP_HOOG_BINNEN / ($GEM_COP[0] * 7.5) / $factor;
            } Else {
                $kw = $KW_TEMP_HOOG[3] / ($GEM_COP[3] * 7.5) / $factor;
            }
        } If ($heatpump->getPeriod() == 4) { // 4 = Mei - September;
            // VLOOKUP (30;AG6:AI85;2;TRUE)/Q50
            If ($pool->getCovering() == "binnenbad") {
                $kw = $KW_TEMP_HOOG_BINNEN / ($GEM_COP[0] * 7.5) / $factor;
            } Else {
                $kw = $KW_TEMP_HOOG[4] / ($GEM_COP[4] * 7.5) / $factor;
            }
        } Else { //Mag nooit gebeuren
            //Geef een juiste zwemperiode op.
        }
        
        $array = array(); //RETURNED
        $array["kw"] = $kw;
        
        $array["pool"] = $pool;
        $array["heatpump"] = $heatpump;
        
        $array["summerpump"] = self::$ZOMERPOMPEN[round($kw) - 1];
        $array["summerpump_alternative"] = self::$ZOMERPOMPEN_ALTERNATIEF[round($kw) - 1];
        $array["allseasonspump"] = self::$ALLSEASONSPOMPEN[round($kw) - 1];
        $array["allseasonspump_alternative"] = self::$ALLSEASONSPOMPEN_ALTERNATIEF[round($kw) - 1];
        return $array;
    }
    
    /************************************************************************/
    /*    DATA                                                              */
    /************************************************************************/
    private static $ZOMERPOMPEN = array(
    array("ZVWX4010", "Harmo Warmtepomp 3 kW zwembad jacuzzi SPA verwarmi", 599),
    array("ZVWX4010", "Harmo Warmtepomp 3 kW zwembad jacuzzi SPA verwarmi", 599),
    array("ZVWX4010", "Harmo Warmtepomp 3 kW zwembad jacuzzi SPA verwarmi", 599),
    array("ZVWX4020", "Harmo Warmtepomp 4,5 kW zwembad verwarming", 795),
    array("ZVWX4020", "Harmo Warmtepomp 4,5 kW zwembad verwarming", 795),
    array("ZVWX4030", "Harmo warmtepomp 8 kW zwembad zwembadverwarming", 1279),
    array("ZVWX4030", "Harmo warmtepomp 8 kW zwembad zwembadverwarming", 1279),
    array("ZVWX4030", "Harmo warmtepomp 8 kW zwembad zwembadverwarming", 1279),
    array("ZVWX4040", "Harmo warmtepomp 10 kW zwembad zwembadverwarming", 1499),
    array("ZVWX4040", "Harmo warmtepomp 10 kW zwembad zwembadverwarming", 1499),
    array("ZVWX4050", "Harmo warmtepomp 12 kW zwembad zwembadverwarming", 1889),
    array("ZVWX4050", "Harmo warmtepomp 12 kW zwembad zwembadverwarming", 1889),
    array("ZVWX1030", "Duratech Warmtepomp 13 kW zwembad verwarming én jac", 2475),
    array("ZVWX1040", "Duratech 18 kW zwembad én jacuzzi", 3290),
    array("ZVWX1040", "Duratech 18 kW zwembad én jacuzzi", 3290),
    array("ZVWX1040", "Duratech 18 kW zwembad én jacuzzi", 3290),
    array("ZVWX1040", "Duratech 18 kW zwembad én jacuzzi", 3290),
    array("ZVWX1050", "Duratech 22 kW zwembad én jacuzzi", 3990),
    array("ZVWX1050", "Duratech 22 kW zwembad én jacuzzi", 3990),
    array("ZVWX1050", "Duratech 22 kW zwembad én jacuzzi", 3990),
    array("ZVWX1050", "Duratech 22 kW zwembad én jacuzzi", 3990),
    array("ZVWX1050", "Duratech 22 kW zwembad én jacuzzi", 3990),
    array("ZVWX1060", "Duratech 25 kW zwembad triphase én jaccuzi", 4795),
    array("ZVWX1060", "Duratech 25 kW zwembad triphase én jaccuzi", 4795),
    array("ZVWX1060", "Duratech 25 kW zwembad triphase én jaccuzi", 4795),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0));
    private static $ZOMERPOMPEN_ALTERNATIEF = array(
    array("ZVWX8110", "WG Warmtepomp 2.5 KW", 749),
    array("ZVWX8110", "WG Warmtepomp 2.5 KW", 749),
    array("ZVWX8120", "WG Warmtepomp 5 KW", 949),
    array("ZVWX8120", "WG Warmtepomp 5 KW", 949),
    array("ZVWX8120", "WG Warmtepomp 5 KW", 949),
    array("ZVWX8130", "WG Warmtepomp 8 KW", 1531,64),
    array("ZVWX8130", "WG Warmtepomp 8 KW", 1531,64),
    array("ZVWX8130", "WG Warmtepomp 8 KW", 1531,64),
    array("ZVWX8140", "WG Warmtepomp 12 KW", 2205,57),
    array("ZVWX8140", "WG Warmtepomp 12 KW", 2205,57),
    array("ZVWX8140", "WG Warmtepomp 12 KW", 2205,57),
    array("ZVWX8140", "WG Warmtepomp 12 KW", 2205,57),
    array("ZVWX1030", "Duratech Warmtepomp 13 kW zwembad verwarming én jac", 2475),
    array("ZVWX6110", "Perfect temp warmtepomp 14 kW", 2790),
    array("ZVWX5050", "Harmo Plus 18 kW", 3650),
    array("ZVWX5050", "Harmo Plus 18 kW", 3650),
    array("ZVWX5050", "Harmo Plus 18 kW", 3650),
    array("ZVWX5050", "Harmo Plus 18 kW", 3650),
    array("ZVWX6120", "Warmtepomp Perfect Temp 20 kW voor Zwembad jacuzz", 4450),
    array("ZVWX6120", "Warmtepomp Perfect Temp 20 kW voor Zwembad jacuzz", 4450),
    array("ZVWX6130", "Warmtepomp Perfect Temp 25 kW voor Zwembad jacuzz", 4800),
    array("ZVWX6130", "Warmtepomp Perfect Temp 25 kW voor Zwembad jacuzz", 4800),
    array("ZVWX6130", "Warmtepomp Perfect Temp 25 kW voor Zwembad jacuzz", 4800),
    array("ZVWX6130", "Warmtepomp Perfect Temp 25 kW voor Zwembad jacuzz", 4800),
    array("ZVWX6130", "Warmtepomp Perfect Temp 25 kW voor Zwembad jacuzz", 4800),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0));
    private static $ALLSEASONSPOMPEN = array(
    array("ZVWX4050", "Harmo warmtepomp 12 kW zwembad zwembadverwarming", 1890),
    array("ZVWX5010", "Harmo Plus 5 kW", 1395),
    array("ZVWX5010", "Harmo Plus 5 kW", 1395),
    array("ZVWX5010", "Harmo Plus 5 kW", 1395),
    array("ZVWX5020", "Harmo Plus 6.5 kW", 1559),
    array("ZVWX5020", "Harmo Plus 6.5 kW", 1559),
    array("ZVWX5030", "Harmo Plus 8.5 kW", 1995),
    array("ZVWX5030", "Harmo Plus 8.5 kW", 1995),
    array("ZVWX5040", "Harmo Plus 12 kW", 2950),
    array("ZVWX5040", "Harmo Plus 12 kW", 2950),
    array("ZVWX5040", "Harmo Plus 12 kW", 2950),
    array("ZVWX5040", "Harmo Plus 12 kW", 2950),
    array("ZVWX5050", "Harmo Plus 18 kW", 3650),
    array("ZVWX5050", "Harmo Plus 18 kW", 3650),
    array("ZVWX5050", "Harmo Plus 18 kW", 3650),
    array("ZVWX5050", "Harmo Plus 18 kW", 3650),
    array("ZVWX5050", "Harmo Plus 18 kW", 3650),
    array("ZVWX5050", "Harmo Plus 18 kW", 3650),
    array("ZVWX2040", "Duratech Plus 19.6 kW zwembad én jacuzzi", 5875),
    array("ZVWX2050", "Duratech Plus 22 kW zwembad én jacuzzi", 6500),
    array("ZVWX2050", "Duratech Plus 22 kW zwembad én jacuzzi", 6500),
    array("ZVWX2050", "Duratech Plus 22 kW zwembad én jacuzzi", 6500),
    array("ZVWX2060", "Duratech Plus 30 kW zwembad én jacuzzi", 7250),
    array("ZVWX2060", "Duratech Plus 30 kW zwembad én jacuzzi", 7250),
    array("ZVWX2060", "Duratech Plus 30 kW zwembad én jacuzzi", 7250),
    array("ZVWX2060", "Duratech Plus 30 kW zwembad én jacuzzi", 7250),
    array("ZVWX2060", "Duratech Plus 30 kW zwembad én jacuzzi", 7250),
    array("ZVWX2060", "Duratech Plus 30 kW zwembad én jacuzzi", 7250),
    array("ZVWX2060", "Duratech Plus 30 kW zwembad én jacuzzi", 7250),
    array("ZVWX2060", "Duratech Plus 30 kW zwembad én jacuzzi", 7250),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0));
    private static $ALLSEASONSPOMPEN_ALTERNATIEF = array(
    array("ZVWX2010", "Duratech Plus 7 kW zwembad en jacuzzi", 2250),
    array("ZVWX2010", "Duratech Plus 7 kW zwembad en jacuzzi", 2250),
    array("ZVWX2010", "Duratech Plus 7 kW zwembad en jacuzzi", 2250),
    array("ZVWX2010", "Duratech Plus 7 kW zwembad en jacuzzi", 2250),
    array("ZVWX2010", "Duratech Plus 7 kW zwembad en jacuzzi", 2250),
    array("ZVWX2010", "Duratech Plus 7 kW zwembad en jacuzzi", 2250),
    array("ZVWX2010", "Duratech Plus 7 kW zwembad en jacuzzi", 2250),
    array("ZVWX2020", "Duratech Plus 10 kW zwembad en jacuzzi", 3445),
    array("ZVWX2020", "Duratech Plus 10 kW zwembad en jacuzzi", 3445),
    array("ZVWX2020", "Duratech Plus 10 kW zwembad en jacuzzi", 3445),
    array("ZVWX2030", "Duratech Plus 14.3 kW zwembad en jacuzzi", 4250),
    array("ZVWX2030", "Duratech Plus 14.3 kW zwembad en jacuzzi", 4250),
    array("ZVWX2030", "Duratech Plus 14.3 kW zwembad en jacuzzi", 4250),
    array("ZVWX2030", "Duratech Plus 14.3 kW zwembad en jacuzzi", 4250),
    array("ZVWX2040", "Duratech Plus 19.6 kW zwembad en jacuzzi", 5875),
    array("ZVWX2040", "Duratech Plus 19.6 kW zwembad en jacuzzi", 5875),
    array("ZVWX2040", "Duratech Plus 19.6 kW zwembad en jacuzzi", 5875),
    array("ZVWX2040", "Duratech Plus 19.6 kW zwembad en jacuzzi", 5875),
    array("ZVWX6120", "Warmtepomp Perfect Temp 20 kW voor Zwembad jacuzz", 4450),
    array("ZVWX6120", "Warmtepomp Perfect Temp 20 kW voor Zwembad jacuzz", 4450),
    array("ZVWX6130", "Warmtepomp Perfect Temp 25 kW voor Zwembad jacuzz", 4800),
    array("ZVWX6130", "Warmtepomp Perfect Temp 25 kW voor Zwembad jacuzz", 4800),
    array("ZVWX6130", "Warmtepomp Perfect Temp 25 kW voor Zwembad jacuzz", 4800),
    array("ZVWX6130", "Warmtepomp Perfect Temp 25 kW voor Zwembad jacuzz", 4800),
    array("ZVWX6130", "Warmtepomp Perfect Temp 25 kW voor Zwembad jacuzz", 4800),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6220", "Warmtepomp perfect heat 32 kW voor Zwembad jacuzz", 5999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("ZVWX6230", "Warmtepomp perfect heat 37,5 kW voor Zwembad jacu", 6999),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0),
    array("reg", "Geen warmtepomp beschikbaar", 0));
}