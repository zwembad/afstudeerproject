<?php
require(DIR_WS_CLASSES. 'Tile.php');
require(DIR_WS_CLASSES. 'Voegsel.php');

class StoneCalculationStrategy implements CalculationStrategy {

	private $_stoneCategory;
	private $_tiles;
	
    function __construct($_stoneCategory, $_tiles)
    {
		$this->setTiles($_tiles);
		$this->setStoneCategory($_stoneCategory);
    }
	
	public function setStoneCategory($_stoneCategory)
    {
        $this->_stoneCategory = $_stoneCategory;
    }

    public function getStoneCategory()
    {
        return $this->_stoneCategory;
    }
	
	public function setTiles($_tiles)
    {
        $this->_tiles = $_tiles;
    }

    public function getTiles()
    {
        return $this->_tiles;
    }

    public function calculatePrice($pool, $products){
		$mapper = new PropAttributeMapper(4);
		$borderstoneArray = array();
		for($i=0; $i< count($products); $i++){
			$product = $products[$i];
			$attributes = $mapper->getPropAttributesForRefInternal($product[1]);
			$borderstone = new Borderstone($product[0], $product[1], $this->getStoneCategory(), '',$attributes['Hoogte (cm)'], $attributes['Lengte (cm)'], $product[2], $attributes['Breedte (cm)'], $attributes['Boordsteen vorm']);
			if(!is_null($attributes['Boordsteen vorm'])){ 
				$borderstoneArray[$attributes['Boordsteen vorm']] = $borderstone;
			}else if(!is_null($attributes['Tegel'])){
				$tileProduct = new Tile($product[0], $product[1]);
			}else if(!is_null($attributes['Voegsel'])){
				$voegselProduct = new Voegsel($product[0], $product[1]);
			}
		}
		
		if($this->getStoneCategory() == "Natuursteen"){
			$straightStone = $borderstoneArray['rechte'];
			$innerCornerStone = $borderstoneArray['afgeronde binnenhoek'];
			$outerCornerStoneLeft = $borderstoneArray['linkse buitenhoek'];
			$outerCornerStoneRight = $borderstoneArray['rechtse buitenhoek'];
			$curvedCornerStone = $borderstoneArray['ronde'];
		}else if($this->getStoneCategory() == "Boordsteen beton"){
			$straightStone = $borderstoneArray['rechte'];
			$innerCornerStone = $borderstoneArray['binnenhoek'];
			$outerCornerStoneLeft = $borderstoneArray['buitenhoek'];
			$outerCornerStoneRight = $borderstoneArray['buitenhoek'];
			$curvedCornerStone = $borderstoneArray['boog met afgeronde neus'];
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
			if($pool->getLength() >= $pool->getWidth()){
				$metersStraight = 2* ($pool->getLength() - $pool->getWidth());
			}else{
				$metersStraight = 2* ($pool->getWidth() - $pool->getLength());
			}
			if($pool->getLength() > $pool->getWidth()){
				$metersRound = $pool->getWidth() * 3.16;
			}else{
				$metersRound = $pool->getLength() * 3.16;
			}
       }

       $length = $borderstoneArray['rechte']->getLength() + 0.000001;

       $numberStraight = 1 + floor($metersStraight/($length/100) + 0.5); //floor 12/50/100

       if ($length > 1){
           $numberCurved = floor($metersRound/($length/100) + 0.5);
       } else {
           $numberCurved = 0;
       }

       if (!is_null($this->getTiles())){
           $numberOfTiles = $numberStraight + $numberCurved + (3 * $numberInnerCorner);
       } else {
           $numberOfTiles = 0;
       }

       if ($this->getStoneCategory() == "Boordsteen beton"){
           $voegsel = floor(($numberStraight/30) + 0.5);
       } else {
           $voegsel = 0;
       }

       $transport = floor(($numberStraight + $numberInnerCorner + $numberOuterCornerLeft + $numberOuterCornerRight
                + $numberCurved + $numberOfTiles + $voegsel)/40 + 0.99);

	   $priceStraight = $numberStraight * $straightStone->getPrice();
	   $priceInnerCorner = $numberInnerCorner * $innerCornerStone->getPrice();
	   $priceOuterCornerLeft = $numberOuterCornerLeft * $outerCornerStoneLeft->getPrice();
       $priceOuterCornerRight = $numberOuterCornerRight * $outerCornerStoneRight->getPrice();
       $priceCurved = $numberCurved * $curvedCornerStone->getPrice();

	   if(!is_null($tileProduct)){
			$priceTiles = $numberOfTiles * $tileProduct->getPrice();
	   }
	   if(!is_null($voegselProduct)){
			$priceVoegsel = $voegsel * $voegselProduct->getPrice();
	   }
       $priceTransport = $transport * 42.35;

       $priceTotal = $priceStraight + $priceInnerCorner + $priceOuterCornerLeft + $priceOuterCornerRight + $priceCurved + $priceTiles
           + $priceVoegsel + $priceTransport;

		$array ['borderstonesStraight'] = $numberStraight;
	   $array['refBorderstonesStraight'] = $straightStone->getReferenceInternal();
	   $array['unitPriceBorderstonesStraight'] = $straightStone->getPrice();
	   
       $array ['borderstonesInnerCorner'] = $numberInnerCorner;
	   $array['refBorderstonesInnerCorner'] = $innerCornerStone->getReferenceInternal();
	   $array['unitPriceBorderstonesInnerCorner'] = $innerCornerStone->getPrice();
	   
       $array ['borderstonesOuterCornerLeft'] = $numberOuterCornerLeft;
	   $array['refBorderstonesOuterCornerLeft'] = $outerCornerStoneLeft->getReferenceInternal();
	   $array['unitPriceBorderstonesOuterCornerLeft'] = $outerCornerStoneLeft->getPrice();
	   
       $array ['borderstonesOuterCornerRight'] = $numberOuterCornerRight;
	   $array['refBorderstonesOuterCornerRight'] = $outerCornerStoneRight->getReferenceInternal();
	   $array['unitPriceBorderstonesOuterCornerRight'] = $outerCornerStoneRight->getPrice();
	   
       $array ['borderstonesCurved'] = $numberCurved;
	   $array['refBorderstonesCurved'] = $curvedCornerStone->getReferenceInternal();
	   $array['unitPriceBorderstonesCurved'] = $curvedCornerStone->getPrice();
       $array ['tiles'] = $numberOfTiles;
	    if(!is_null($tileProduct)){
	 	    $array['refTiles'] = $tileProduct->getReferenceInternal();
			$array['unitPriceTiles'] = $tileProduct->getPrice();
		}
       $array ['voegsel'] = $voegsel;
	     if(!is_null($voegselProduct)){
			$array['unitPriceVoegsel'] = $voegselProduct->getPrice();
			$array ['refVoegsel'] = $voegselProduct->getReferenceInternal();
	   }
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
	   
       return $array;
	}
} 