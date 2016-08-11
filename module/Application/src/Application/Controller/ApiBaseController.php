<?php


namespace Application\Controller;


use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\MvcEvent;


class ApiBaseController extends AbstractRestfulController
{


    public function onDispatch(MvcEvent $e){

        $authSuccessful = false;

        if ($e->getRequest()->getHeaders()->get('Authorization')) {

            $authorisation = $e->getRequest()->getHeaders()->get('Authorization')->getFieldValue();
            $authHash = substr($authorisation, 6);

            $authDecoded = base64_decode($authHash);

            $authPieces = explode(":", $authDecoded, 2);
            $authUser = isset($authPieces[0]) ? $authPieces[0] : null;
            $authPassword = isset($authPieces[1]) ? $authPieces[1] : null;

            if ($authUser) {

                $authservice = $e->getApplication()->getServiceManager()->get('AuthService');

                $authservice->getAdapter()
                    ->setIdentity($authUser)
                    ->setCredential($authPassword);

                $result = $authservice->authenticate();


                if ($result->isValid()) {

                    $authSuccessful = true;
                    parent::onDispatch($e);
                }

            }
        }

        if(!$authSuccessful) {
            //This API user has not authenticated send them an authentication challenge
            $e->getApplication()->getResponse()->setStatusCode(401);
            $e->getApplication()->getResponse()->getHeaders()->addHeaderLine('WWW-Authenticate', 'Basic');
            return $e->getResponse();
        }
    }



    protected function methodNotAllowed()
    {
        $this->response->setStatusCode(405);
    }

    # Override default actions as they do not return valid JsonModels
    public function create($data)
    {
        $this->methodNotAllowed();
    }


    public function delete($id)
    {
        $this->methodNotAllowed();
    }


    public function deleteList($data)
    {
        $this->methodNotAllowed();
    }


    public function get($id)
    {
        $this->methodNotAllowed();
    }


    public function getList()
    {
        $this->methodNotAllowed();
    }


    public function head($id = null)
    {
        $this->methodNotAllowed();
    }


    public function options()
    {
        $this->methodNotAllowed();
    }


    public function patch($id, $data)
    {
        $this->methodNotAllowed();
    }


    public function replaceList($data)
    {
        $this->methodNotAllowed();
    }


    public function patchList($data)
    {
        $this->methodNotAllowed();
    }


    public function update($id, $data)
    {
        $this->methodNotAllowed();
    }
}