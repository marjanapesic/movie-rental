<?php

namespace ApplicationTest;

use Zend\Mvc;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;

class bootstrap
{
    static $serviceManager;

    static function go()
    {
        // Setup autoloading
        require_once( __DIR__ . '/../public/init_autoloader.php' );

        // Run application
        $config = require(__DIR__ .'/../config/application.config.php');
        \Zend\Mvc\Application::init($config);

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        self::$serviceManager = $serviceManager;
    }

    static public function getServiceManager()
    {
        return self::$serviceManager;
    }
}

bootstrap::go();


//chdir(dirname(__DIR__));
//
//include 'public/init_autoloader.php';
//
//
//Zend\Mvc\Application::init(include 'config/application.config.php');