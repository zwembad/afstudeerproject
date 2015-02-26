<?php

require_once('Pool.php');
require_once('Borderstone.php');
require_once('StoneCalculationStrategy.php');

/* function __construct($_depth, $_diameter, $_length, $_shape, $_type, $_width) */
$pool = new Pool(0, 0, 2, 3, 0, 4);

/* function __construct($_category, $_color, $_length, $_material, $_tiles, $_type, $_width) */
$borderstone = new Borderstone("natuursteen", 0, 50, "black panda", false, "type neus", 35);

$calculationStrategy = new StoneCalculationStrategy();

$array = $calculationStrategy->calculatePrice($pool, $borderstone);
var_dump($array);

echo $array ['borderstonesStraight'];
echo $array ['priceBorderstonesStraight'];
