<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 24/02/15
 * Time: 12:02
 */

require_once('CalculationStrategy.php');

class StoneCalculationStrategy implements CalculationStrategy {

    function __construct()
    {
    }

    public function calculatePrice($pool, $borderstone){
        $metersRound = 0;
        $numberOfBorderstonesInnerCorner = 0;
        $numberOfBorderstonesOuterCornerLeft = 0;
        $numberOfBorderstonesOuterCornerRight = 0;
       if ($pool->getShape() == 1){ //Rechthoekig
           $metersStraight = ($pool->getLength() * 2) + ($pool->getWIdth() * 2);
           $numberOfBorderstonesInnerCorner = 4;
           $numberOfBorderstonesOuterCornerLeft = 0;
           $numberOfBorderstonesOuterCornerRight = 0;
       } elseif ($pool->getShape() == 2){ // "Rechthoekig met rom trap"
           $metersStraight = (2 * ($pool->getLength() + $pool->getWidth())) - $pool->getDiameter();
           $metersRound = $pool->getDiameter() * 3.16/2;
           $numberOfBorderstonesInnerCorner = 4;
           $numberOfBorderstonesOuterCornerLeft = 1;
           $numberOfBorderstonesOuterCornerRight = 1;
       } elseif ($pool->getShape() == 3){ //"Rechthoekig met rechthoekige trap"
           $metersStraight = ($pool->getLength() * 2) + ($pool->getWidth() * 2); //+ $pool->getDiameter(); //+C40
           $numberOfBorderstonesInnerCorner = 4;
           $numberOfBorderstonesOuterCornerLeft = 1;
           $numberOfBorderstonesOuterCornerRight = 1;
       } elseif ($pool->getShape() == 4){ // Rond
           $metersRound = $pool->getDiameter() * 3.16;
           $metersStraight = 0;
       } elseif ($pool->getShape() == 5){ //"Ovaal"
           $metersStraight = 2* ($pool->getLength() - $pool->getWidth());
           $metersRound = $pool->getWidth() * 3.16;
       }

       $length = $borderstone->getLength() + 0.000001;

       $numberOfBorderstonesStraight = 1 + floor($metersStraight/($length/100) + 0.5); //floor 12/50/100

       if ($length > 1){
           $numberOfBorderstonesCurved = floor($metersRound/($length/100) + 0.5);
       } else {
           $numberOfBorderstonesCurved = 0;
       }

       if ($borderstone->getTiles()){
           $numberOfTiles = $numberOfBorderstonesStraight + $numberOfBorderstonesCurved + (3 * $numberOfBorderstonesInnerCorner);
       } else {
           $numberOfTiles = 0;
       }

       if ($borderstone->getMaterial() == "betonsteen"){
           $voegsel = floor(($numberOfBorderstonesStraight/30) + 0.5);
       } else {
           $voegsel = 0;
       }

       $transport = floor(($numberOfBorderstonesStraight + $numberOfBorderstonesInnerCorner + $numberOfBorderstonesOuterCornerLeft + $numberOfBorderstonesOuterCornerRight
                + $numberOfBorderstonesCurved + $numberOfTiles + $voegsel)/40 + 0.99);

       $priceBorderstonesStraight = $numberOfBorderstonesStraight * 24;
       $priceBorderstonesInnerCorner = $numberOfBorderstonesInnerCorner * 43;
       $priceBorderstonesOuterCornerLeft = $numberOfBorderstonesOuterCornerLeft * 21;
       $priceBorderstonesOuterCornerRight = $numberOfBorderstonesOuterCornerRight * 21;
       $priceBorderstonesCurved = $numberOfBorderstonesCurved * 36;
       $priceTiles = $numberOfTiles * 21;
       $priceVoegsel = $voegsel * 21;
       $priceTransport = $transport * 42.35;

       $priceTotal = $priceBorderstonesStraight + $priceBorderstonesInnerCorner + $priceBorderstonesOuterCornerLeft + $priceBorderstonesOuterCornerRight + $priceBorderstonesCurved + $priceTiles
           + $priceVoegsel + $priceTransport;

       $array ['borderstonesStraight'] = $numberOfBorderstonesStraight;
       $array ['borderstonesInnerCorner'] = $numberOfBorderstonesInnerCorner;
       $array ['borderstonesOuterCornerLeft'] = $numberOfBorderstonesOuterCornerLeft;
       $array ['borderstonesOuterCornerRight'] = $numberOfBorderstonesOuterCornerRight;
       $array ['borderstonesCurved'] = $numberOfBorderstonesCurved;
       $array ['tiles'] = $numberOfTiles;
       $array ['voegsel'] = $voegsel;
       $array ['transport'] = $transport;

       $array ['priceBorderstonesStraight'] = $priceBorderstonesStraight;
       $array ['priceBorderstonesInnerCorner'] = $priceBorderstonesInnerCorner;
       $array ['priceBorderstonesOuterCornerLeft'] = $priceBorderstonesOuterCornerLeft;
       $array ['priceBorderstonesOuterCornerRight'] = $priceBorderstonesOuterCornerRight;
       $array ['priceBorderstonesCurved'] = $priceBorderstonesCurved;
       $array ['priceTiles'] = $priceTiles;
       $array ['priceVoegsel'] = $priceVoegsel;
       $array ['priceTransport'] = $priceTransport;
       $array ['priceTotal'] = $priceTotal;

       // $array = array('borderstonesStraight' => $numberOfBorderstonesStraight);

       return $array;
}
} 