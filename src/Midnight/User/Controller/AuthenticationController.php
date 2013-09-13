<?php

namespace Midnight\User\Controller;

use Doctrine\ORM\EntityManager;
use Midnight\User\Authentication\Adapter;
use Midnight\User\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthenticationController extends AbstractActionController
{
    public function loginAction()
    {
        if ($this->identity()) {
            return $this->redirect()->toRoute('zfcadmin');
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
                    return $this->redirect()->toRoute('zfcadmin');
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
}