<?php

namespace Midnight\UserModule\Controller;

use Midnight\User\Service\UserServiceInterface;
use Midnight\User\Storage\StorageInterface;
use Midnight\UserModule\Entity\User;
use Midnight\UserModule\Form\SetPasswordForm;
use Midnight\UserModule\Form\UserForm;
use Zend\Form\FormInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class UserAdminController
 * @package Midnight\UserModule\Controller
 *
 * @method Request getRequest()
 */
class UserAdminController extends AbstractActionController
{
    public function indexAction()
    {
        $users = $this->getUserStorage()->getAll();

        $vm = new ViewModel(array('users' => $users));
        $vm->setTemplate('midnight/user-module/user-admin/index.phtml');
        return $vm;
    }

    public function editUserAction()
    {
        $user = $this->getUserStorage()->loadByIdentity($this->params()->fromRoute('identity'));
        $passwordForm = new SetPasswordForm();
        $passwordForm->setAttribute(
            'action',
            $this->url()->fromRoute('zfcadmin/user/set_password', array('identity' => $user->getIdentity()))
        );

        $vm = new ViewModel(array('user' => $user, 'passwordForm' => $passwordForm));
        $vm->setTemplate('midnight/user-module/user-admin/edit.phtml');
        return $vm;
    }

    public function setPasswordAction()
    {
        $user = $this->getUserStorage()->loadByIdentity($this->params()->fromRoute('identity'));
        $passwordForm = new SetPasswordForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $passwordForm->setData($request->getPost());
            if ($passwordForm->isValid()) {
                $data = $passwordForm->getData(FormInterface::VALUES_AS_ARRAY);
                $this->getUserService()->setCredential($user, $data['password']);
                $this->getUserStorage()->save($user);
                $this->flashMessenger()->addSuccessMessage(sprintf(
                    'The new password for %s was successfully set.',
                    $user->getIdentity()
                ));
                return $this->redirect()->toRoute('zfcadmin/user/edit', array('identity' => $user->getIdentity()));
            }
        }

        $vm = new ViewModel(array('user' => $user, 'passwordForm' => $passwordForm));
        $vm->setTemplate('midnight/user-module/user-admin/edit.phtml');
        return $vm;
    }

    public function createUserAction()
    {
        $form = new UserForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $user = $this->getUserService()->register($data['email'], $data['password']);
                if ($user instanceof User) {
                    $user->setIsAdmin($data['is_admin']);
                }
                $this->getUserStorage()->save($user);
                $this->flashMessenger()->addSuccessMessage('The user was successfully created.');
                return $this->redirect()->toRoute('zfcadmin/user');
            }
        }

        $vm = new ViewModel(array('form' => $form));
        $vm->setTemplate('midnight/user-module/user-admin/create-user.phtml');
        return $vm;
    }

    /**
     * @return StorageInterface
     */
    private function getUserStorage()
    {
        return $this->getServiceLocator()->get('user.storage');
    }

    /**
     * @return UserServiceInterface
     */
    private function getUserService()
    {
        return $this->getServiceLocator()->get('user.service');
    }
} 
