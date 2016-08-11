<?php

namespace Application\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SignUpFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {


        $signUpForm = new \Application\Form\SignUp();
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');

        $signUpForm->setRpUser($em->getRepository('Application\Entity\User'));
        return $signUpForm;
    }
}