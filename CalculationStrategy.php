<?php

interface CalculationStrategy
{
    public function calculatePrice($pool, $borderstone);
}
