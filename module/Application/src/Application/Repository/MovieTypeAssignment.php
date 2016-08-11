<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

use Application\Entity\Movie as MovieEntity;

class MovieTypeAssignment extends EntityRepository
{

    /**
     * @param MovieEntity $movie
     * @param \DateTime $date
     *
     * @return \Application\Entity\MovieTypeAssignment|null
     */
    public function getMovieTypeAssignment(MovieEntity $movie, \DateTime $date = null){

        if(is_null($date)){
            $date = new \DateTime();
        }

        $q = $this->createQueryBuilder('p')
            ->where('p.movie = '.$movie->getMovieId())
            ->andWhere('p.startDate is null or p.startDate <= :date')
            ->andWhere('p.endDate is null or p.endDate >= :date')
            ->setParameter('date', $date->format("Y-m-d"))
            ->getQuery();


        $movieTypeAssignments = $q->getResult();

        return $movieTypeAssignments[0];
    }
}