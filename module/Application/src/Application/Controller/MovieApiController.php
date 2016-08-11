<?php


namespace Application\Controller;

use Zend\View\Model\JsonModel;
use Application\Exception\ApiInvalidParameters;


class MovieApiController extends ApiBaseController
{

    public function get($id)
    {
        $rpMovie = $this->getEntityManager()->getRepository('Application\Entity\Movie');

        /** @var \Application\Entity\Movie $movie */
        $movie = $rpMovie->find($id);

        if(is_null($movie)){
            throw new ApiInvalidParameters("Movie does not exist");
        }

        return new JsonModel($movie->toArray());

    }


    public function getList()
    {
        $includeAvailability = $this->params()->fromRoute('availability');

        /** @var \Application\Repository\Movie $rpMovie */
        $rpMovie = $this->getEntityManager()->getRepository('Application\Entity\Movie');

        if($includeAvailability)
        {
            $result = [];
            foreach($rpMovie->getArrayRentableMovies() as $rentableMovieArray)
            {
                if(isset($rentableMovieArray['releaseDate']))
                {
                    $rentableMovieArray['releaseDate'] = $rentableMovieArray['releaseDate']->format("Y-m-d");
                }
                $result[] = $rentableMovieArray;
            }
            return new JsonModel($result);
        }

        $movies = $rpMovie->findAll();

        $result = [];

        /** @var \Application\Entity\Movie $movie */
        foreach($movies as $movie)
        {
            $result[] = $movie->toArray();
        }

        return new JsonModel($result);
    }


    public function create($data)
    {
        //setting optional parameters
        if(!array_key_exists('releaseDate', $data)){
            $data['releaseDate'] = null;
        }

        /** @var \Application\Form\AddMovie $addMovieForm */
        $addMovieForm = $this->getServiceLocator()->get('Application\Form\AddMovie');
        $addMovieForm->setData($data);

        if ($addMovieForm->isValid()) {
            $movie = new \Application\Entity\Movie();
            $movie->fromArray($addMovieForm->getData());

            $this->getEntityManager()->persist($movie);
            $this->getEntityManager()->flush();

            return new JsonModel(['status' => "OK", 'movieId' => $movie->getMovieId()]);
        } else {
            $messages = $addMovieForm->getMessages();
            while(is_array($messages)) {
                $messages = reset($messages);
            }
            throw new ApiInvalidParameters($messages);
        }

    }



    public function update($id, $data)
    {
        /** @var \Application\Entity\Movie $movie */
        $movie = $this->getEntityManager()->getRepository('Application\Entity\Movie')->find($id);
        $movie->fromArray($data);

        /** @var \Application\Form\EditMovie $editMovieForm */
        $editMovieForm = $this->getServiceLocator()->get('Application\Form\EditMovie');
        $editMovieForm->setData($movie->toArray());

        if($editMovieForm->isValid()){


            //$movie = $this->getEntityManager()->getRepository('Application\Entity\Movie')->find($id);

            $movie->fromArray($editMovieForm->getData());

            $this->getEntityManager()->persist($movie);
            $this->getEntityManager()->flush();

            return new JsonModel($movie->toArray());
        }
        else{
            $messages = $editMovieForm->getMessages();
            while(is_array($messages)) {
                $messages = reset($messages);
            }
            throw new ApiInvalidParameters($messages);
        }
    }


    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }

}