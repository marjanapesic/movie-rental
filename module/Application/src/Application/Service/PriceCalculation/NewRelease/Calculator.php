<?php

namespace Application\Service\PriceCalculation\NewRelease;

use Application\Service\PriceCalculation\CalculatorInterface;
use Application\ValueObject\Price;


class Calculator implements CalculatorInterface {

    /** @var  Price */
    protected $premiumPrice;

    public function __construct(Price $premiumPrice)
    {
        $this->premiumPrice = $premiumPrice;
    }


    public function calculatePrice($numberOfDays) {

        return $this->premiumPrice->mul($numberOfDays);

    }


    public function calculateSubcharge($numberOfDays){

        return $this->premiumPrice->mul($numberOfDays);

    }
}