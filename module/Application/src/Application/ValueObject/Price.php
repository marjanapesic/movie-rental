<?php

namespace Application\ValueObject;


class Price {

    private $amount;

    private $currency;


    public function __construct($amount, $currency){

        $this->amount = $amount;

        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }


    public function toArray()
    {
        return ['amount' => $this->getAmount(), 'currency' => $this->getCurrency()];
    }


    public function mul($multiplier)
    {
        return new Price($this->getAmount() * $multiplier, $this->getCurrency());
    }


    /**
     * TODO: When support for multiple currencies is added we need to extend this method
     *
     * @param Price $price
     * @return bool
     */
    public function equals(Price $price)
    {
        return $price->getAmount() == $this->getAmount() && $price->getCurrency() == $this->getCurrency();
    }

    /**
     * TODO: When support for multiple currencies is added we need to extend this method
     *
     * @param Price $price
     * @return Price
     */
    public function add(Price $price){
        return new Price($this->getAmount() + $price->getAmount(), $this->getCurrency());
    }
}