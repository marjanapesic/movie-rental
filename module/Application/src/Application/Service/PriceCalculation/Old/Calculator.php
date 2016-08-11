<?php

namespace Application\Service\PriceCalculation\Old;


use Application\Service\PriceCalculation\CalculatorInterface;
use Application\ValueObject\Price;


class Calculator implements CalculatorInterface {

    /** @var  Price */
    protected $basicPrice;

    public function __construct(Price $basicPrice)
    {
        $this->basicPrice = $basicPrice;
    }

    public function calculatePrice($numberOfDays) {

        return ($numberOfDays <= 5)
            ? $this->basicPrice
            : $this->basicPrice->mul($numberOfDays - 4);
    }


    public function calculateSubcharge($numberOfDays){

        return $this->basicPrice->mul($numberOfDays);
    }
}