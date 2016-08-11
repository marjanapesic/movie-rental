<?php

namespace Application\Service\PriceCalculation;


use Application\Entity\MovieRental;
use Application\Entity\Movie;
use Application\ValueObject\Price;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class Service implements ServiceLocatorAwareInterface, ServiceInterface {

    protected $serviceLocator;


    /*
     * @return Price
     */
    public function calculateMovieRentalPrice(Movie $movie, \DateTime $startDate, \DateTime $endDate){

        $numberOfDays = $endDate->diff($startDate)->days ? $endDate->diff($startDate)->days : 1;

        /** @var \Application\Repository\MovieTypeAssignment $rpMovieTypeAssignment */
        $rpMovieTypeAssignment = $this->getEntityManager()->getRepository('Application\Entity\MovieTypeAssignment');

        $movieTypeAssignment = $rpMovieTypeAssignment->getMovieTypeAssignment($movie, $startDate);


        /** @var \Application\Service\PriceCalculation\StrategyFactory $strategyFactory */
        $strategyFactory = $this->getServiceLocator()->get('Application\Service\PriceCalculation\StrategyFactory');

        /** @var \Application\Service\PriceCalculation\CalculatorInterface $strategy */
        $strategy = $strategyFactory->getStrategy($movieTypeAssignment->getMovieTypeIdentifier());

        return $strategy->calculatePrice($numberOfDays);
    }


    public function calculateMovieRentalSubcharge(MovieRental $movieRental, \DateTime $returnDate){

        $movie = $movieRental->getMovie();

        $numberOfDays = $returnDate->diff($movieRental->getEndDate())->d;


        /** @var \Application\Repository\MovieTypeAssignment $rpMovieTypeAssignment */
        $rpMovieTypeAssignment = $this->getEntityManager()->getRepository('Application\Entity\MovieTypeAssignment');
        $movieTypeAssignment = $rpMovieTypeAssignment->getMovieTypeAssignment($movie, $movieRental->getStartDate());


        /** @var \Application\Service\PriceCalculation\StrategyFactory $strategyFactory */
        $strategyFactory = $this->getServiceLocator()->get('Application\Service\PriceCalculation\StrategyFactory');

        /** @var \Application\Service\PriceCalculation\CalculatorInterface $strategy */
        $strategy = $strategyFactory->getStrategy($movieTypeAssignment->getMovieTypeIdentifier());

        return $strategy->calculateSubcharge($numberOfDays);
    }



    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {

       return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

    }


    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }


    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}