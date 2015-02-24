<?php
/*function __construct($_depth, $_diameter, $_length, $_shape, $_type, $_width)*/
$pool = new Pool(0, 0, 2, "Rechthoekig", 0, 4);
$borderstone = new Borderstone(0, 50, 0, 0, 35);

$calculationStrategy = new StoneCalculationStrategy();

$array [] = $calculationStrategy->calculatePrice($pool, $borderstone);

echo $array ['borderstonesStraight'];
