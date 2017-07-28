<?php

namespace Midnight\UserModule\Controller;

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
                $auth_adapter = $this->getServiceLocator()->get(\Midnight\User\Authentication\Adapter::class);
                $auth_adapter->setIdentity($data['email']);
                $auth_adapter->setCredential($data['password']);
                $result = $auth_service->authenticate($auth_adapter);
                if ($result->isValid()) {
                    return $this->getNextRedirect();
                }
            }
        }

        return $this->getViewModel(['form' => $form]);
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

    private function setNext($next)
    {
        $this->getSession()->next = $next;
    }

    /**
     * @return string
     * @throws \Zend\Session\Exception\InvalidArgumentException
     * @throws \Zend\Mvc\Exception\RuntimeException
     * @throws \Zend\Mvc\Exception\InvalidArgumentException
     * @throws \Zend\Mvc\Exception\DomainException
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
     * @throws \Zend\Session\Exception\InvalidArgumentException
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
     * @throws \Zend\Mvc\Exception\RuntimeException
     * @throws \Zend\Mvc\Exception\InvalidArgumentException
     * @throws \Zend\Mvc\Exception\DomainException
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
