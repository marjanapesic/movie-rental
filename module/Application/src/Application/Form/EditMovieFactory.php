<?php

namespace Application\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EditMovieFactory implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');

        return new EditMovie($em->getRepository('Application\Entity\Movie'), $em->getRepository('Application\Entity\MovieRental'));
    }
}