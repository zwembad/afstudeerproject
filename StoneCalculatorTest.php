<?php

require_once('Pool.php');
require_once('Borderstone.php');
require_once('StoneCalculationStrategy.php');

/* function __construct($_depth, $_diameter, $_length, $_shape, $_type, $_width) */
$pool = new Pool(0, 3, 0, "Rond", 0, 0);

/* function __construct($_color, $_length, $_material, $_tiles, $_width) */
$borderstone = new Borderstone(0, 50, "natuursteen", false, 35);

$calculationStrategy = new StoneCalculationStrategy();

$array = $calculationStrategy->calculatePrice($pool, $borderstone);
var_dump($array);

echo $array ['borderstonesStraight'];
echo $array ['priceBorderstonesStraight'];
