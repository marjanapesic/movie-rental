<?php
namespace ApplicationTest\Service\PriceCalculation\Regular;

use PHPUnit_Framework_TestCase;

use Application\ValueObject\Price;
use Application\Service\PriceCalculation\Regular\Calculator;

class CalculatorTest extends \PHPUnit_Framework_TestCase {

    /** @var Calculator  */
    protected $calculator;

    function setUp()
    {
        $basicPrice = new Price(30, "SEK");
        $this->calculator = new Calculator($basicPrice);
    }

    function tearDown()
    {
        unset($this->calculator);
    }

    function testCalculatePriceSuccess()
    {
        /** @var Price $result */
        $result = $this->calculator->calculatePrice(3);
        $expected = new Price(30, "SEK");
        $this->assertEquals($expected->getAmount(), $result->getAmount());
        $this->assertEquals($expected->getCurrency(), $result->getCurrency());

        /** @var Price $result */
        $result = $this->calculator->calculatePrice(5);
        $expected = new Price(90, "SEK");
        $this->assertEquals($expected->getAmount(), $result->getAmount());
        $this->assertEquals($expected->getCurrency(), $result->getCurrency());
    }

    function testCalculateSubchargeSuccess()
    {
        /** @var Price $result */
        $result = $this->calculator->calculateSubcharge(5);
        $expected = new Price(150, "SEK");
        $this->assertEquals($expected->getAmount(), $result->getAmount());
        $this->assertEquals($expected->getCurrency(), $result->getCurrency());
    }
}