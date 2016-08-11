<?php


namespace Application\Controller;


use Application\Exception\ApiInvalidParameters;
use Zend\View\Model\JsonModel;


class MovieReturnApiController extends ApiBaseController
{

    public function create($data){

        $result = [];

        /** @var \Application\Service\MovieRental\Service $movieRentalService */
        $movieRentalService = $this->getServiceLocator()->get('Application\Service\MovieRental\Service');

        $this->getEntityManager()->getConnection()->beginTransaction();

        try {

            if(!isset($data['rentIdentifier']) || !is_array($data['rentIdentifier']) || !count($data['rentIdentifier'])){
                throw new \Exception("Missing rental identifiers.", "1");
            }


            foreach ($data['rentIdentifier'] as $movieRentalId) {

                /** @var \Application\Repository\MovieRental $rpMovieRental */
                $rpMovieRental = $this->getEntityManager()->getRepository('Application\Entity\MovieRental');

                /** @var \Application\Entity\MovieRental $movieRental */
                $movieRental = $rpMovieRental->find($movieRentalId);

                if(!$movieRental){
                   throw new \Exception("Invalid identifier.", "2");
                }


                $movieRental = $movieRentalService->returnMovie($movieRental);

                $result[$movieRentalId]['subcharge'] = ['amount' => $movieRental->getSubchargeAmount(), 'currency' => $movieRental->getSubchargeCurrency()];
                $result[$movieRentalId]['bonusPoints'] = $movieRental->getBonusPoints();
            }

            $this->getEntityManager()->getConnection()->commit();
        }
        catch(\Exception $e)
        {
            $this->getEntityManager()->getConnection()->rollBack();
            throw new ApiInvalidParameters($e->getMessage());
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