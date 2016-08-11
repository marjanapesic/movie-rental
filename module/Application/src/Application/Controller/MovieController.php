<?php


namespace Application\Controller;

use Application\Entity\Movie;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class MovieController extends AbstractActionController
{

    public function addAction(){


        $addMovieForm = $this->getServiceLocator()->get('Application\Form\AddMovie');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if($request->isPost()){

            $addMovieForm->setData($request->getPost());
            if ($addMovieForm->isValid()) {
                $movie = new Movie();
                $data = $addMovieForm->getData();
                $movie->setTitle($data['title']);
                $movie->setReleaseDate(new \DateTime($data['releaseDate']));
                $movie->setNumberOfCopies($data['numberOfCopies']);

                $this->getEntityManager()->persist($movie);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('index',  ['action' => 'list']);
            }
        }

        return new ViewModel(array('form' => $addMovieForm));
    }



    public function editAction()
    {
        $movieId = $this->params()->fromQuery('id');

        /** @var \Application\Entity\Movie $movie */
        $movie = $this->getEntityManager()->getRepository('Application\Entity\Movie')->find($movieId);

        /** @var \Application\Form\EditMovie $editMovie */
        $editMovie = $this->getServiceLocator()->get('Application\Form\EditMovie');

        $editMovie->get($editMovie::FIELD_MOVIE_ID)->setValue($movie->getMovieId());
        $editMovie->get($editMovie::FIELD_TITLE)->setValue($movie->getTitle());
        $editMovie->get($editMovie::FIELD_RELEASE_DATE)->setValue($movie->getReleaseDate()->format('Y-m-d'));
        $editMovie->get($editMovie::FIELD_NUMBER_OF_COPIES)->setValue($movie->getNumberOfCopies());

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if($request->isPost()) {
            $editMovie->setData($request->getPost());
            if ($editMovie->isValid()) {

                $data = $editMovie->getData();

                /** @var \Application\Entity\Movie $movie */
                $movie = $this->getEntityManager()->getRepository('Application\Entity\Movie')->find($data[$editMovie::FIELD_MOVIE_ID]);

                $movie->setTitle($data[$editMovie::FIELD_TITLE]);
                $movie->setReleaseDate(new \DateTime($data[$editMovie::FIELD_RELEASE_DATE]));
                $movie->setNumberOfCopies($data[$editMovie::FIELD_NUMBER_OF_COPIES]);

                $this->getEntityManager()->persist($movie);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('index', ['action' => 'list']);
            }
        }

        return new ViewModel(array('form' => $editMovie));
    }


    public function listAction()
    {
        /** @var \Application\Repository\Movie $rpMovies */
        $rpMovies = $this->getEntityManager()->getRepository('Application\Entity\Movie');

        $movies = $rpMovies->getArrayRentableMovies();

        return new ViewModel(array('movies' => $movies));
    }


    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }
}
