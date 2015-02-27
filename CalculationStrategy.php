<?php

interface CalculationStrategy
{
    public function calculatePrice($pool, $product);
}
