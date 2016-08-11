<?php

namespace Application\Service\PriceCalculation;


interface CalculatorInterface {

    /**
     * @param int $numberOfDays
     * @return \Application\ValueObject\Price;
     */
    public function calculatePrice($numberOfDays);

    /**
     * @param int $numberOfDays
     * @return \Application\ValueObject\Price;
     */
    public function calculateSubcharge($numberOfDays);

}