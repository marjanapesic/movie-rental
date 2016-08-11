<?php

//config/autoload/doctrine.global.php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'doctrine_type_mappings' => array(
                    'enum' => 'string'
                )
            ),
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Entity\User',
                'identity_property' => 'email',
                'credential_property' => 'passwordHash',
                'credential_callable' => 'Application\Service\User\Service::verifyHashedPassword'
            ),
        ),
    )
);