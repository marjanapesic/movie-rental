<?php
namespace Application\EntityListener;


use Application\Entity\MovieTypeAssignment;
use Application\Repository\SystemSetting as RpSystemSetting;
use Application\Entity\Movie as MovieEntity;

class Movie {

    /** @var  \Doctrine\ORM\EntityManager */
    private $entityManager;



    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    public function postPersist(\Doctrine\ORM\Event\LifecycleEventArgs $eventArgs)
    {

        if($eventArgs->getEntity() instanceof MovieEntity) {

            /** @var MovieEntity $movie */
            $movie = $eventArgs->getEntity();

            /** @var RpSystemSetting $rpSystemSetting */
            $rpSystemSetting = $this->entityManager->getRepository('Application\Entity\SystemSetting');

            /** @var \Application\Entity\SystemSetting $numberOfDaysNewReleaseTypeSetting */
            $numberOfDaysNewReleaseTypeSetting = $rpSystemSetting->getSetting('number_of_days_new_release_type');
            $numberOfDaysNewReleaseType = $numberOfDaysNewReleaseTypeSetting->getSettingValue();

            /** @var \Application\Entity\SystemSetting $numberOfDaysRegularTypeSetting */
            $numberOfDaysRegularTypeSetting = $rpSystemSetting->getSetting('number_of_days_regular_type');
            $numberOfDaysRegularType = $numberOfDaysRegularTypeSetting->getSettingValue();

            $date = $movie->getReleaseDate() ? $movie->getReleaseDate() : new \DateTime();

            $newReleaseUntil = clone $date;
            $newReleaseUntil->add(new \DateInterval('P' . $numberOfDaysNewReleaseType . 'D'));

            $regularUntil = clone $newReleaseUntil;
            $regularUntil->add(new \DateInterval('P' . $numberOfDaysRegularType . 'D'));


            $movieTypeAssignment = new MovieTypeAssignment();
            $movieTypeAssignment->setMovie($movie);
            $movieTypeAssignment->setStartDate($movie->getReleaseDate());
            $movieTypeAssignment->setEndDate($newReleaseUntil);
            $movieTypeAssignment->setMovieTypeIdentifier($movieTypeAssignment::NEW_TYPE);

            $this->entityManager->persist($movieTypeAssignment);
            $this->entityManager->flush();


            $movieTypeAssignment = new MovieTypeAssignment();
            $movieTypeAssignment->setMovie($movie);
            $movieTypeAssignment->setStartDate($newReleaseUntil->add(new \DateInterval("P1D")));
            $movieTypeAssignment->setEndDate($regularUntil);
            $movieTypeAssignment->setMovieTypeIdentifier($movieTypeAssignment::REGULAR_TYPE);

            $this->entityManager->persist($movieTypeAssignment);
            $this->entityManager->flush();

            $movieTypeAssignment = new MovieTypeAssignment();
            $movieTypeAssignment->setMovie($movie);
            $movieTypeAssignment->setStartDate($regularUntil->add(new \DateInterval("P1D")));
            $movieTypeAssignment->setEndDate(null);
            $movieTypeAssignment->setMovieTypeIdentifier($movieTypeAssignment::OLD_TYPE);

            $this->entityManager->persist($movieTypeAssignment);
            $this->entityManager->flush();
        }

    }
}