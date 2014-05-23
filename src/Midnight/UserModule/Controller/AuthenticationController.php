<?php

namespace Midnight\UserModule\Controller;

use Doctrine\ORM\EntityManager;
use Midnight\UserModule\Authentication\Adapter;
use Midnight\UserModule\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;

class AuthenticationController extends AbstractActionController
{
    /**
     * @var Container
     */
    private $session;

    public function dispatch(RequestInterface $request, Response $response = null)
    {
        if ($request instanceof \Zend\Http\Request) {
            $next = $request->getQuery('next');
            if ($next) {
                $this->setNext($next);
            }
        }
        return parent::dispatch($request, $response);
    }

    public function loginAction()
    {
        if ($this->identity()) {
            return $this->getNextRedirect();
        }

        $form = new LoginForm();

        /** @var $request Request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                /** @var $auth_service AuthenticationService */
                $auth_service = $this->getServiceLocator()->get('auth');
                /** @var $auth_adapter Adapter */
                $auth_adapter = $this->getServiceLocator()->get('Midnight\User\Authentication\Adapter');
                $auth_adapter->setIdentity($data['email']);
                $auth_adapter->setCredential($data['password']);
                $result = $auth_service->authenticate($auth_adapter);
                if ($result->isValid()) {
                    return $this->getNextRedirect();
                }
            }
        }

        return $this->getViewModel(array('form' => $form));
    }

    public function logoutAction()
    {
        /** @var $auth_service AuthenticationService */
        $auth_service = $this->getServiceLocator()->get('auth');
        $auth_service->clearIdentity();
        $this->redirect()->toRoute('home');
    }

    private function getViewModel($variables = null)
    {
        $vm = new ViewModel($variables);
        $vm->setTemplate('user/authentication/' . $this->params()->fromRoute('action') . '.phtml');
        return $vm;
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }

    private function setNext($next)
    {
        $this->getSession()->next = $next;
    }

    /**
     * @return string
     */
    private function getNext()
    {
        $next = $this->getSession()->next;
        if (!$next) {
            $next = $this->getDefaultNext();
        }
        return $next;
    }

    /**
     * @return Container
     */
    private function getSession()
    {
        if (!$this->session) {
            $this->session = new Container('midnight_user_auth');
        }
        return $this->session;
    }

    /**
     * @return string
     */
    private function getDefaultNext()
    {
        return $this->url()->fromRoute('zfcadmin');
    }

    private function getNextRedirect()
    {
        return $this->redirect()->toUrl($this->getNext());
    }
}
