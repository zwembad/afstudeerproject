<?php

class StoneCalculationStrategy implements CalculationStrategy {
	
    function __construct()
    {
    }

    public function calculatePrice($pool, $products){
		$mapper = new PropAttributeMapper(4);
		$borderstoneArray = array();
		for($i=0; $i< count($products); $i++){
			$product = $products[$i];
			$attributes = $mapper->getPropAttributesForRefInternal($product[1]);
			$borderstone = new Borderstone($product[0], $product[1], 'natuursteen', 0,$attributes['Hoogte (cm)'], $attributes['Lengte (cm)'], $product[2], 0, 'zonder neus', $attributes['Breedte (cm)'], $attributes['Boordsteen vorm']);
			if(!is_null($attributes['Boordsteen vorm'])){ 
				$borderstoneArray[$attributes['Boordsteen vorm']] = $borderstone;
			}
		}
		
		
		$metersRound = 0;
        $numberInnerCorner = 0;
        $numberOuterCornerLeft = 0;
        $numberOuterCornerRight = 0;
       if ($pool->getShape() == 1){ //Rechthoekig
           $metersStraight = ($pool->getLength() * 2) + ($pool->getWidth() * 2);
           $numberInnerCorner = 4;
           $numberOuterCornerLeft = 0;
           $numberOuterCornerRight = 0;
       } elseif ($pool->getShape() == 2){ // "Rechthoekig met rom trap"
           $metersStraight = (2 * ($pool->getLength() + $pool->getWidth())) - $pool->getDiameter();
           $metersRound = $pool->getDiameter() * 3.16/2;
           $numberInnerCorner = 4;
           $numberOuterCornerLeft = 1;
           $numberOuterCornerRight = 1;
       } elseif ($pool->getShape() == 3){ //"Rechthoekig met rechthoekige trap"
           $metersStraight = ($pool->getLength() * 2) + ($pool->getWidth() * 2); //+ $pool->getDiameter(); //+C40
           $numberInnerCorner = 4;
           $numberOuterCornerLeft = 1;
           $numberOuterCornerRight = 1;
       } elseif ($pool->getShape() == 4){ // Rond
           $metersRound = $pool->getDiameter() * 3.16;
           $metersStraight = 0;
       } elseif ($pool->getShape() == 5){ //"Ovaal"
           $metersStraight = 2* ($pool->getLength() - $pool->getWidth());
           $metersRound = $pool->getWidth() * 3.16;
       }

       $length = $borderstoneArray['rechte']->getLength() + 0.000001;

       $numberStraight = 1 + floor($metersStraight/($length/100) + 0.5); //floor 12/50/100

       if ($length > 1){
           $numberCurved = floor($metersRound/($length/100) + 0.5);
       } else {
           $numberCurved = 0;
       }

       if ($borderstoneArray['rechte']->getTiles()){
           $numberOfTiles = $numberStraight + $numberCurved + (3 * $numberInnerCorner);
       } else {
           $numberOfTiles = 0;
       }

       if ($borderstoneArray['rechte']->getMaterial() == "betonsteen"){
           $voegsel = floor(($numberStraight/30) + 0.5);
       } else {
           $voegsel = 0;
       }

       $transport = floor(($numberStraight + $numberInnerCorner + $numberOuterCornerLeft + $numberOuterCornerRight
                + $numberCurved + $numberOfTiles + $voegsel)/40 + 0.99);

       $priceStraight = $numberStraight * $borderstoneArray['rechte']->getPrice();
	   $priceInnerCorner = $numberInnerCorner * $borderstoneArray['afgeronde binnenhoek']->getPrice();
       $priceOuterCornerLeft = $numberOuterCornerLeft * $borderstoneArray['linkse buitenhoek']->getPrice();
       $priceOuterCornerRight = $numberOuterCornerRight * $borderstoneArray['rechtse buitenhoek']->getPrice();
       $priceCurved = $numberCurved * $borderstoneArray['ronde']->getPrice();
       $priceTiles = $numberOfTiles * 21;
       $priceVoegsel = $voegsel * 21;
       $priceTransport = $transport * 42.35;

       $priceTotal = $priceStraight + $priceInnerCorner + $priceOuterCornerLeft + $priceOuterCornerRight + $priceCurved + $priceTiles
           + $priceVoegsel + $priceTransport;

	  // var_dump($borderstoneArray['rechte']->getReferenceInternal());
       $array ['borderstonesStraight'] = $numberStraight;
	   $array['refBorderstonesStraight'] = $borderstoneArray['rechte']->getReferenceInternal();
	   $array['unitPriceBorderstonesStraight'] = $borderstoneArray['rechte']->getPrice();
	   
       $array ['borderstonesInnerCorner'] = $numberInnerCorner;
	   $array['refBorderstonesInnerCorner'] = $borderstoneArray['afgeronde binnenhoek']->getReferenceInternal();
	   $array['unitPriceBorderstonesInnerCorner'] = $borderstoneArray['afgeronde binnenhoek']->getPrice();
	   
       $array ['borderstonesOuterCornerLeft'] = $numberOuterCornerLeft;
	   $array['refBorderstonesOuterCornerLeft'] = $borderstoneArray['linkse buitenhoek']->getReferenceInternal();
	   $array['unitPriceBorderstonesOuterCornerLeft'] = $borderstoneArray['linkse buitenhoek']->getPrice();
	   
       $array ['borderstonesOuterCornerRight'] = $numberOuterCornerRight;
	   $array['refBorderstonesOuterCornerRight'] = $borderstoneArray['rechtse buitenhoek']->getReferenceInternal();
	   $array['unitPriceBorderstonesOuterCornerRight'] = $borderstoneArray['rechtse buitenhoek']->getPrice();
	   
       $array ['borderstonesCurved'] = $numberCurved;
	   $array['refBorderstonesCurved'] = $borderstoneArray['ronde']->getReferenceInternal();
	   $array['unitPriceBorderstonesCurved'] = $borderstoneArray['ronde']->getPrice();
       $array ['tiles'] = $numberOfTiles;
       $array ['voegsel'] = $voegsel;
       $array ['transport'] = $transport;

       $array ['priceStraight'] = $priceStraight;
       $array ['priceInnerCorner'] = $priceInnerCorner;
       $array ['priceOuterCornerLeft'] = $priceOuterCornerLeft;
       $array ['priceOuterCornerRight'] = $priceOuterCornerRight;
       $array ['priceCurved'] = $priceCurved;
       $array ['priceTiles'] = $priceTiles;
       $array ['priceVoegsel'] = $priceVoegsel;
       $array ['priceTransport'] = $priceTransport;
       $array ['priceTotal'] = $priceTotal;
	   
	   //var_dump($array);
       return $array;
	}
} 