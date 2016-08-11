<?php


namespace Application\Controller;


use Zend\View\Model\JsonModel;
use Application\Exception\ApiInvalidParameters;

class MoviePriceApiController extends ApiBaseController
{

    public function get($id){

        $start = new \DateTime();

        $endDate = $this->params()->fromRoute('endDate');

        if(is_null($endDate)){
            throw new ApiInvalidParameters("Missing end date.");
        }

        $end = \date_create_from_format("Y-m-d", $endDate);

        if(!$end){
            throw new ApiInvalidParameters("Wrong date format.");
        }

        if($end->getTimestamp() < $start->getTimestamp()){
            throw new ApiInvalidParameters("End date cannot be before today.");
        }


        /** @var \Application\Entity\Movie $movie */
        $movie = $this->getEntityManager()->getRepository('Application\Entity\Movie')->find($id);

        if(is_null($movie)){
            throw new ApiInvalidParameters("Wrong movie id.");
        }

        /** @var \Application\Service\PriceCalculation\Service $priceCalculationService */
        $priceCalculationService = $this->getServiceLocator()->get('Application\Service\PriceCalculation\Service');

        $price = $priceCalculationService->calculateMovieRentalPrice($movie, new \DateTime(), $end);

        return new JsonModel($price->toArray());
    }


    public function getList(){

        $start = new \DateTime();

        $endDate = $this->params()->fromRoute('endDate');

        if(is_null($endDate)){
            throw new ApiInvalidParameters("Missing end date.");
        }

        $end = \date_create_from_format("Y-m-d", $endDate);

        if(!$end){
            throw new ApiInvalidParameters("Wrong date format.");
        }

        if($end->getTimestamp() < $start->getTimestamp()){
            throw new ApiInvalidParameters("End date cannot be before today.");
        }

        $ids = $this->params("ids");

        $rpMovie = $this->getEntityManager()->getRepository('Application\Entity\Movie');
        $movies = $ids ? $rpMovie->findBy(['movieId' => explode(",", $ids)]) : $rpMovie->findAll();


        /** @var \Application\Service\PriceCalculation\Service $priceCalculationService */
        $priceCalculationService = $this->getServiceLocator()->get('Application\Service\PriceCalculation\Service');


        $result = [];

        /** @var \Application\Entity\Movie $movie */
        foreach($movies as $movie) {
            $price = $priceCalculationService->calculateMovieRentalPrice($movie, $start, $end);

            $result[$movie->getMovieId()] = $price->toArray();
        }



        return new JsonModel($result);
    }



    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }
}