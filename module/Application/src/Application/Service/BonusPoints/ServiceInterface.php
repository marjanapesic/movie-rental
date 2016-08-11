<?php

namespace Application\Service\BonusPoints;

use Application\Entity\MovieRental;

interface ServiceInterface {

    /*
    * @return int
    */
    public function calculateBonusPoints(MovieRental $movieRental);
}