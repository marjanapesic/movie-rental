<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getAutoloaderConfig()
    {
         
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


    public function onBootstrap(MvcEvent $e) {

        $sm = $e->getApplication()->getServiceManager();

        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 0);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'), 0);


        /** @var \Doctrine\ORM\EntityManager $em */
        $entityManager = $sm->get('doctrine.entitymanager.orm_default');

        $dem = $entityManager->getEventManager();

        $dem->addEventListener(array( \Doctrine\ORM\Events::postPersist), new \Application\EntityListener\Movie($entityManager) );

    }

    public function onDispatch(MvcEvent $e)
    {

    }

    public function onDispatchError($e)
    {
        return $this->getJsonModelError($e);
    }

    public function getJsonModelError(MvcEvent $e)
    {
        $error = $e->getError();
        if (!$error) {
            return;
        }


        $exception = $e->getParam('exception');
        $message = null;
        if ($exception) {
            if($exception instanceof \Application\Exception\ApiInvalidParameters){
                $e->getResponse()->setStatusCode(404);
            }

            $message = $exception->getMessage();

        }
        if ($error == 'error-router-no-match') {
            $message = 'Resource not found.';
        }

        $model = new \Zend\View\Model\JsonModel(array('error' => array('message' => $message)));
        $e->setResult($model);
        return $model;
    }
}