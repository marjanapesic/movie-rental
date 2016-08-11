<?php


namespace Application\Controller;


use Application\ValueObject\Price;
use Application\Exception\ApiInvalidParameters;
use Zend\View\Model\JsonModel;

class MovieRentApiController extends ApiBaseController
{

    public function create($data){

        $authService = $this->getServiceLocator()->get('AuthService');
        $user = $authService->getIdentity();


        $start = new \DateTime();

        /** @var \Application\Service\MovieRental\Service $movieRentalService */
        $movieRentalService = $this->getServiceLocator()->get('Application\Service\MovieRental\Service');


        /** @var Price $totalPrice */
        $totalPrice = null;

        $result = [];

        $this->getEntityManager()->getConnection()->beginTransaction();

        try {

            foreach ($data as $movieId => $rentalDetails) {
                $endDate = $rentalDetails['end-date'];

                if (is_null($endDate)) {
                    throw new \Exception("Missing end date.");
                }

                $end = \date_create_from_format("Y-m-d", $endDate);

                if (!$end) {
                    throw new \Exception("Wrong date format.");
                }

                if ($end->getTimestamp() < $start->getTimestamp()) {
                    throw new \Exception("End date cannot be before today.");
                }

                if (!isset($rentalDetails['amount']) || !isset($rentalDetails['currency'])) {
                    throw new \Exception("Missing price amount or currency.");
                }


                $movie = $this->getEntityManager()->getRepository('Application\Entity\Movie')->find($movieId);

                $movieRental = $movieRentalService->rentMovie($movie, $user, $end, new Price($rentalDetails['amount'], $rentalDetails['currency']));

                $result[] = ['movieId'=> $movieId, 'rentIdentifier' => $movieRental->getMovieRentalId(), 'amount' => $movieRental->getPriceAmount(), 'currency' => $movieRental->getPriceCurrency()];

                $movieRentalPrice = new Price($movieRental->getPriceAmount(), $movieRental->getPriceCurrency());

                $totalPrice = is_null($totalPrice) ? $movieRentalPrice : $totalPrice->add($movieRentalPrice);
            }

            $this->getEntityManager()->getConnection()->commit();
        }
        catch(\Exception $e)
        {
            $this->getEntityManager()->getConnection()->rollBack();
            throw new ApiInvalidParameters($e->getMessage());
        }


        return new JsonModel(array_merge(['rentals' => $result], ["totalPrice" => ["amount" => $totalPrice->getAmount(), "currency" => $totalPrice->getCurrency()]]));

    }


    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }
}