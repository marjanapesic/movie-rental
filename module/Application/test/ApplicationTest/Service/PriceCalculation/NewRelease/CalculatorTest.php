<?php
namespace ApplicationTest\Service\PriceCalculation\NewRelease;

use PHPUnit_Framework_TestCase;

use Application\ValueObject\Price;
use Application\Service\PriceCalculation\NewRelease\Calculator;

class CalculatorTest extends \PHPUnit_Framework_TestCase {

    /** @var Calculator  */
    protected $calculator;

    function setUp()
    {
        $premiumPrice = new Price(40, "SEK");
        $this->calculator = new Calculator($premiumPrice);
    }

    function tearDown()
    {
        unset($this->calculator);
    }

    function testCalculatePriceSuccess()
    {
        /** @var Price $result */
        $result = $this->calculator->calculatePrice(5);
        $expected = new Price(200, "SEK");
        $this->assertEquals($expected->getAmount(), $result->getAmount());
        $this->assertEquals($expected->getCurrency(), $result->getCurrency());
    }


    function testCalculateSubchargeSuccess()
    {
        /** @var Price $result */
        $result = $this->calculator->calculateSubcharge(5);
        $expected = new Price(200, "SEK");
        $this->assertEquals($expected->getAmount(), $result->getAmount());
        $this->assertEquals($expected->getCurrency(), $result->getCurrency());
    }

}