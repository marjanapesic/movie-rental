<?php

namespace Application\Service\PriceCalculation;

use Application\Entity\MovieTypeAssignment;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StrategyFactory implements ServiceLocatorAwareInterface {

    /** @var  ServiceLocatorInterface */
    protected $serviceLocator;



    public function getStrategy($movieType) {

        $priceCalculationStrategyIdentifier = null;

        switch($movieType) {

            case MovieTypeAssignment::NEW_TYPE:
                $priceCalculationStrategyIdentifier = 'Application\Service\PriceCalculation\NewReleaseStrategy';
                break;
            case MovieTypeAssignment::REGULAR_TYPE:
                $priceCalculationStrategyIdentifier = 'Application\Service\PriceCalculation\RegularStrategy';
                break;
            case MovieTypeAssignment::OLD_TYPE:
                $priceCalculationStrategyIdentifier = 'Application\Service\PriceCalculation\OldStrategy';
                break;
        }

        if(is_null($priceCalculationStrategyIdentifier))
        {
            throw new \Exception("Could not determine price calculation strategy for given movie type: ".$movieType);
        }

        return $this->getServiceLocator()->get($priceCalculationStrategyIdentifier);
    }



    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }


    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}