<?php

namespace Application\Service\Auth;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class AuthServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('doctrine.authenticationservice.orm_default');
    }
}