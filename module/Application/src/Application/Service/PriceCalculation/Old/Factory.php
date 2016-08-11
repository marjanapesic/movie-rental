<?php


namespace Application\Service\PriceCalculation\Old;

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

        /** @var \Application\Entity\SystemSetting $priceAmountSetting */
        $priceAmountSetting = $rpSystemSettings->getSetting('regular_price_amount');

        /** @var \Application\Entity\SystemSetting $priceCurrencySetting */
        $priceCurrencySetting = $rpSystemSettings->getSetting('regular_price_currency');


        $premiumPrice = new Price($priceAmountSetting->getSettingValue(), $priceCurrencySetting->getSettingValue());

        return new Calculator($premiumPrice);

    }
}