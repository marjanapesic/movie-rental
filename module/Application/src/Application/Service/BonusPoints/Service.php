<?php

namespace Application\Service\BonusPoints;

use Application\Entity\MovieRental;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Service implements ServiceInterface, ServiceLocatorAwareInterface {

    protected $serviceLocator;

    /*
    * @return int
    */
    public function calculateBonusPoints(MovieRental $movieRental)
    {

        $movie = $movieRental->getMovie();
        $startDate = $movieRental->getStartDate();

        /** @var \Application\Repository\MovieTypeAssignment $rpMovieTypeAssignment */
        $rpMovieTypeAssignment = $this->getEntityManager()->getRepository('Application\Entity\MovieTypeAssignment');
        $movieTypeAssignment = $rpMovieTypeAssignment->getMovieTypeAssignment($movie, $startDate);

        return $movieTypeAssignment->getMovieTypeIdentifier() == $movieTypeAssignment::NEW_TYPE ? 2 : 1;

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