<?php


namespace Application\Service\PriceCalculation\NewRelease;

use Application\ValueObject\Price;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;


class Factory implements FactoryInterface{


    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var \Application\Repository\SystemSetting $rpSystemSettings */
        $rpSystemSettings = $em->getRepository('Application\Entity\SystemSetting');

        /** @var \Application\Entity\SystemSetting $premiumPriceAmountSetting */
        $premiumPriceAmountSetting = $rpSystemSettings->getSetting('premium_price_amount');

        /** @var \Application\Entity\SystemSetting $premiumPriceCurrencySetting */
        $premiumPriceCurrencySetting = $rpSystemSettings->getSetting('premium_price_currency');


        $premiumPrice = new Price($premiumPriceAmountSetting->getSettingValue(), $premiumPriceCurrencySetting->getSettingValue());

        return new Calculator($premiumPrice);

    }
}