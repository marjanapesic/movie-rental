<?php

namespace Application\Service\Auth;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class ApiAuthorizationChecker extends AbstractListenerAggregate
{

    public function attach(EventManagerInterface $eventManager)
    {
        $this->listeners[] = $eventManager->attach(
            MvcEvent::EVENT_DISPATCH, function ($event) {
            $this->checkApiAuthorization($event);
        }, 100
        );
    }

    public function checkApiAuthorization(MvcEvent $e)
    {
        $authSuccessful = false;

        if ($e->getRequest()->getHeaders()->get('Authorization')) {

            $authorisation = $e->getRequest()->getHeaders()->get('Authorization')->getFieldValue();
            $authHash = substr($authorisation, 6);
            $authDecoded = base64_decode($authHash);

            $authPieces = explode(":", $authDecoded, 2);
            $authUser = null;
            $authPassword = null;
            if (isset($authPieces[0])) {
                $authUser = $authPieces[0];
            }
            if (isset($authPieces[1])) {
                $authPassword = $authPieces[1];
            }

            $sm = $e->getApplication()->getServiceManager();
            if ($authUser) {

                $authservice = $sm->get('AuthService');

                $authservice->getAdapter()
                    ->setIdentity($authUser)
                    ->setCredential($authPassword);

                $result = $authservice->authenticate();


                if ($result->isValid()) {

                    $authSuccessful = true;
                }

            }
        }

        return $authSuccessful;
    }
}