<?php

namespace Midnight\UserModule\Controller;

use Midnight\User\Service\UserServiceInterface;
use Midnight\UserModule\Authentication\Adapter;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Form\FormInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class AuthenticationController
 * @package Midnight\UserModule\Controller
 *
 * @method Request getRequest()
 */
class AuthenticationController extends AbstractActionController
{
    public function registerAction()
    {
        $form = $this->getSignupForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $identity = $data['email'];
                $credential = $data['password'];
                $this->getUserService()->register($identity, $credential);
                $adapter = $this->getAuthenticationAdapter();
                $adapter->setIdentity($identity);
                $adapter->setCredential($credential);
                $result = $this->getAuthenticationService()->authenticate($adapter);
                if (!$result->isValid()) {
                    throw new \Exception(sprintf(
                        'User was created, but couldn\'t log in. (%s)',
                        $result->getCode()
                    ));
                }
                return $this->redirect()->toRoute($this->getPostLoginRoute());
            }
        }

        $vm = new ViewModel(array('form' => $form));
        $vm->setTemplate('midnight/user-module/authentication/register.phtml');
        return $vm;
    }

    public function loginAction()
    {
        $form = $this->getLoginForm();

        $next = $this->params()->fromQuery('next');
        if ($next) {
            $form->get('next')->setValue($next);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $adapter = $this->getAuthenticationAdapter();
                $adapter->setIdentity($data['email']);
                $adapter->setCredential($data['password']);
                $result = $this->getAuthenticationService()->authenticate($adapter);
                if ($result->isValid()) {
                    if (!empty($data['next'])) {
                        return $this->redirect()->toUrl($data['next']);
                    } else {
                        return $this->redirect()->toRoute($this->getPostLoginRoute());
                    }
                }
            }
        }

        $vm = new ViewModel(array('form' => $form));
        $vm->setTemplate('midnight/user-module/authentication/login.phtml');
        return $vm;
    }

    public function logoutAction()
    {
        $auth = $this->getAuthenticationService();
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
        }
        return $this->redirect()->toRoute($this->getPostLogoutRoute());
    }

    /**
     * @return FormInterface
     */
    private function getSignupForm()
    {
        return $this->getServiceLocator()->get('user.signup.form');
    }

    /**
     * @return FormInterface
     */
    private function getLoginForm()
    {
        return $this->getServiceLocator()->get('user.login.form');
    }

    /**
     * @return UserServiceInterface
     */
    private function getUserService()
    {
        return $this->getServiceLocator()->get('user.service');
    }

    /**
     * @return AuthenticationServiceInterface
     */
    private function getAuthenticationService()
    {
        return $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
    }

    /**
     * @return Adapter
     */
    private function getAuthenticationAdapter()
    {
        return $this->getServiceLocator()->get('user.authentication.adapter');
    }

    /**
     * @return string
     */
    private function getPostLoginRoute()
    {
        $config = $this->getServiceLocator()->get('Config');
        return $config['user']['post_login_route'];
    }

    private function getPostLogoutRoute()
    {
        $config = $this->getServiceLocator()->get('Config');
        return $config['user']['post_logout_route'];
    }
} 
