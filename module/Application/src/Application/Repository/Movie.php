<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class Movie extends EntityRepository
{

    public function getArrayRentableMovies(){

        $qb = $this->createQueryBuilder('movie')
            ->select("movie.movieId, movie.title, movie.releaseDate, movie.numberOfCopies")
            ->leftJoin('\Application\Entity\MovieRental', 'mr', 'WITH', 'movie.movieId = mr.movie')
            ->addSelect('(movie.numberOfCopies - COUNT(mr)) as availabilityCount')
            ->groupBy('movie.movieId')
            ->getQuery();

        return $qb->getArrayResult();
    }
}