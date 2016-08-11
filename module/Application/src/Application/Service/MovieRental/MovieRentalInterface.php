<?php

namespace Application\Service\MovieRental;

use Application\Entity\Movie;
use Application\Entity\User;
use Application\Entity\MovieRental;
use Application\ValueObject\Price;

interface MovieRentalInterface
{

    /**
     * @param Movie $movie
     * @param User $user
     * @param \DateTime $end
     * @param Price|null $preCalculatedPrice
     * @return MovieRental
     */
    public function rentMovie(Movie $movie, User $user, \DateTime $end, Price $preCalculatedPrice = null);


    public function returnMovie(MovieRental $movieRental);
}