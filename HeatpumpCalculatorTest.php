<?php

require_once('Pool.php');
require_once('Heatpump.php');
require_once('HeatpumpCalculationStrategy.php');

$pool = new Pool(0, 0, 2, 3, 0, 4, 110, "binnenbad", 1, 100);
$heatpump = new Heatpump(0, 0, 5, 2, 1, 25, 20);

$calculationStrategy = new HeatpumpCalculationStrategy();
$array = $calculationStrategy->calculatePrice($pool, $heatpump);
var_dump($array);