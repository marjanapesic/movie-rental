<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;


class SystemSetting extends EntityRepository
{
    private $defaults = [
        'premium_price_amount' => 40,
        'premium_price_currency' => 'SEK',
        'regular_price_amount' => 30,
        'regular_price_currency' => 'SEK',
        'number_of_days_new_release_type' => '365', //~1yr
        'number_of_days_regular_type' => '1825' //~5yrs
    ];



    public function getSetting($settingName){

        $q = $this->createQueryBuilder('p')
            ->where('p.settingName = :settingName')
            ->setParameter('settingName', $settingName)
            ->getQuery();

        $result = $q->getResult();

        if(!count($result) && array_key_exists($settingName, $this->defaults)){
            $result[] = new \Application\Entity\SystemSetting($settingName, $this->defaults[$settingName]);
        }

        return isset($result[0]) ? $result[0] : null;
    }
}