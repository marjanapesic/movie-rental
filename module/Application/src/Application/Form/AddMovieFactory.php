<?php

namespace Application\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AddMovieFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');

        return new AddMovie($em->getRepository('Application\Entity\Movie'));
    }
}