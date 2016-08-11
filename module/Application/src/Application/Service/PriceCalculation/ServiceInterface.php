<?php

namespace Application\Service\PriceCalculation;

use Application\Entity\Movie;
use Application\Entity\MovieRental;
use Application\ValueObject\Price;

interface ServiceInterface {

    /*
    * @return Price
    */
    public function calculateMovieRentalPrice(Movie $movie, \DateTime $startDate, \DateTime $endDate);


    /**
     * @param MovieRental $movieRental
     * @param \DateTime $returnDate
     * @return Price
     */
    public function calculateMovieRentalSubcharge(MovieRental $movieRental, \DateTime $returnDate);
}