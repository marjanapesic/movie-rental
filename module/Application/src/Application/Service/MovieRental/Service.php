<?php

namespace Application\Service\MovieRental;

use Application\Entity\Movie;
use Application\Entity\MovieRental;
use Application\Entity\User;
use Application\ValueObject\Price;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class Service implements MovieRentalInterface, ServiceLocatorAwareInterface {



    protected $serviceLocator;



    public function rentMovie(Movie $movie, User $user, \DateTime $end, Price $preCalculatedPrice = null){

        $start = new \DateTime();
        $start->setTime(0,0);

        $this->getEntityManager()->getConnection()->beginTransaction();

        try {

            //check availability
            /** @var \Application\Repository\MovieRental $rpMovieRental */
            $rpMovieRental = $this->getEntityManager()->getRepository('Application\Entity\MovieRental');

            $availableCopies = $movie->getNumberOfCopies() - count($rpMovieRental->getOpenMovieRentals($movie, $start));

            if ($availableCopies == 0) {
                throw new \Exception("Movie not available");
            }

            /** @var \Application\Service\PriceCalculation\Service $priceCalculationService */
            $priceCalculationService = $this->getServiceLocator()->get('Application\Service\PriceCalculation\Service');
            $price = $priceCalculationService->calculateMovieRentalPrice($movie, $start, $end);

            if($preCalculatedPrice){
                if(!$price->equals($preCalculatedPrice)){
                    throw new \Exception("Received and calculated prices do not match.");
                }
            }


            $movieRental = new MovieRental();

            $movieRental->setMovie($movie);
            $movieRental->setUser($user);
            $movieRental->setStartDate($start);
            $movieRental->setEndDate($end);
            $movieRental->setPriceAmount($price->getAmount());
            $movieRental->setPriceCurrency($price->getCurrency());
            
            
            /** @var \Application\Service\BonusPoints\Service $bonusPointsService */
            $bonusPointsService = $this->getServiceLocator()->get('Application\Service\BonusPoints\Service');
            $bonusPoints = $bonusPointsService->calculateBonusPoints($movieRental);
            
            $movieRental->setBonusPoints($bonusPoints);


            $this->getEntityManager()->persist($movieRental);
            $this->getEntityManager()->flush();


            $this->getEntityManager()->getConnection()->commit();
        }
        catch(\Exception $e){
            $this->getEntityManager()->getConnection()->rollBack();
            throw $e;
        }

        return $movieRental;
    }




    public function returnMovie(MovieRental $movieRental){

        if($movieRental->getReturnDate()){
            throw new \Exception("Movie is already returned.");
        }

        $returnDate = new \DateTime();
        $returnDate->setTime(0, 0);

        /** @var \Application\Service\PriceCalculation\Service $priceCalculationService */
        $priceCalculationService = $this->getServiceLocator()->get('Application\Service\PriceCalculation\Service');
        $subcharge = $priceCalculationService->calculateMovieRentalSubcharge($movieRental, $returnDate);


        /** @var \Application\Service\BonusPoints\Service $bonusPointsService */
        $bonusPointsService = $this->getServiceLocator()->get('Application\Service\BonusPoints\Service');
        $bonusPoints = $bonusPointsService->calculateBonusPoints($movieRental);

        $movieRental->setReturnDate($returnDate);
        $movieRental->setSubchargeAmount($subcharge->getAmount());
        $movieRental->setSubchargeCurrency($subcharge->getCurrency());
        $movieRental->setBonusPoints($bonusPoints);

        $this->getEntityManager()->persist($movieRental);
        $this->getEntityManager()->flush();

        return $movieRental;
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
