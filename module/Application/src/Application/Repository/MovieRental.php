<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

use Application\Entity\Movie as MovieEntity;

class MovieRental extends EntityRepository
{

    /**
     * Returns array of MovieRental that are still open starting from date.
     *
     * @param MovieEntity $movie
     * @param \DateTime $date
     * @return array
     */
    public function getOpenMovieRentals(MovieEntity $movie, \DateTime $date = null){

        if(is_null($date)){
            $date = new \DateTime();
        }

        $q = $this->createQueryBuilder('p')
            ->where('p.movie = :movieId')
            ->andWhere('p.startDate <= :startDate')
            ->andWhere('p.returnDate is null')
            ->setParameter('movieId', $movie->getMovieId())
            ->setParameter('startDate', $date->format("Y-m-d"))
            ->getQuery();

        return $q->getResult();
    }
}