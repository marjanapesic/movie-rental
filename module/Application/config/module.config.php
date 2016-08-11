<?php


return [

    'router' => array(

        'routes' => array(

            'index' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/[:action][:end]',
                    'constrains'     => array(
                        'action' => 'add|list|edit',
                        'end' => '(\/?)$'
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Movie'
                    ),
                ),
            ),


            'login_route' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/login[:end]',
                    'constraints'=> array(
                        'end' => '(\/?)$'
                    ),
                    'defaults' => array(
                        'controller'    => 'Application\Controller\User',
                        'action'        => 'login',
                    ),
                ),
            ),

            'logout_user_route' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/logout[:end]',
                    'constraints'=> array(
                        'end' => '(\/?)$'
                    ),
                    'defaults' => array(

                        'controller'    => 'Application\Controller\User',
                        'action'        => 'logout',
                    ),
                ),
            ),

            'sign_up_route' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/sign-up[:end]',
                    'constraints'=> array(
                        'end' => '(\/?)$'
                    ),
                    'defaults' => array(
                        'controller'    => 'Application\Controller\User',
                        'action'        => 'signUp',
                    ),
                ),
            ),

            'user_route' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/[:end]',
                    'constraints'=> array(
                        'end' => '(\/?)$'
                    ),
                    'defaults' => array(
                        'controller'    => 'Application\Controller\User',
                        'action'        => 'index',
                    ),
                ),
            ),


            'movie-price-api' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/movie-price[/:id][/ids/:ids][/end-date/:endDate][:end]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                        'ids'     => '[0-9,]+',
                        'endDate' => '[0-9\Q-/\E]+',
                        'end' => '(\/?)$'
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\MoviePriceApi',
                    ),
                ),
            ),

            'movie-rent-api' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/movie-rent[:end]',
                    'constraints' => array(
                        'end' => '(\/?)$'
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\MovieRentApi',
                    ),
                ),
            ),

            'movie-return-api' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/movie-return[:end]',
                    'constraints' => array(
                        'end' => '(\/?)$'
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\MovieReturnApi',
                    ),
                ),
            ),

            'movie-rest' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/movie[/id/:id][/availability/:availability][:end]',
                    'constraints' => array(
                        'end' => '(\/?)$',
                        'id'     => '[0-9]+',
                        'availability' => '[0-1]'
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\MovieApi',
                    ),
                ),
            ),
        ),
    ),

    'service_manager' => [
        'invokables' => [
            'Application\Service\PriceCalculation\StrategyFactory' => 'Application\Service\PriceCalculation\StrategyFactory',
            'Application\Service\PriceCalculation\Service' => 'Application\Service\PriceCalculation\Service',
            'Application\Service\BonusPoints\Service' => 'Application\Service\BonusPoints\Service',
            'Application\Service\MovieRental\Service' => 'Application\Service\MovieRental\Service',
            'Application\Service\User\Service' => 'Application\Service\User\Service',
            'Application\Form\Login' => 'Application\Form\LoginForm',
            'Application\Service\Auth\ApiAuthorizationListener' => 'Application\Service\Auth\ApiAuthorizationListener'
        ],
        'factories' => [
            'Application\Service\PriceCalculation\NewReleaseStrategy' => 'Application\Service\PriceCalculation\NewRelease\Factory',
            'Application\Service\PriceCalculation\RegularStrategy' => 'Application\Service\PriceCalculation\Regular\Factory',
            'Application\Service\PriceCalculation\OldStrategy' => 'Application\Service\PriceCalculation\Old\Factory',
            'Application\Form\AddMovie' => 'Application\Form\AddMovieFactory',
            'Application\Form\EditMovie' => 'Application\Form\EditMovieFactory',
            'AuthService' => 'Application\Service\Auth\AuthServiceFactory',
            'Application\Form\SignUp' => 'Application\Form\SignUpFactory',
        ]
    ],

    'controllers' => [
        'invokables' =>[
            'Application\Controller\Movie' => 'Application\Controller\MovieController',
            'Application\Controller\User' => 'Application\Controller\UserController',
            'Application\Controller\MoviePriceApi' => 'Application\Controller\MoviePriceApiController',
            'Application\Controller\MovieReturnApi' => 'Application\Controller\MovieReturnApiController',
            'Application\Controller\MovieRentApi' => 'Application\Controller\MovieRentApiController',
            'Application\Controller\MovieApi' => 'Application\Controller\MovieApiController',
        ]
    ],


    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/500',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/500'				=> __DIR__ . '/../view/error/500.phtml',
            'error/403'				=> __DIR__ . '/../view/error/403.phtml',
            'layout/error'          => __DIR__ . '/../view/layout/error.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'Application_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' =>  'Application_driver'
                ),
            ),
        ),
    )
];