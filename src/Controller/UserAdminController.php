<?php

namespace Midnight\UserModule\Controller;

use Doctrine\ORM\EntityManager;
use Midnight\UserModule\Entity\User;
use Midnight\UserModule\Factory\UserFactory;
use Midnight\UserModule\Form\CreateUserForm;
use Midnight\UserModule\Form\SetPasswordForm;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserAdminController extends AbstractActionController
{
    public function indexAction()
    {
        $users = $this->getEntityManager()->getRepository('Midnight\User\Entity\User')->findAll();
        return $this->getViewModel(array('users' => $users));
    }

    public function createAction()
    {
        $form = new CreateUserForm();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $factory = new UserFactory($this->getServiceLocator());
                $user = $factory->create($data['email'], $data['password']);
                $em = $this->getEntityManager();
                $em->persist($user);
                $em->flush();
                $this->flashMessenger()->addMessage('Der Benutzer wurde erstellt.');
                return $this->redirect()->toRoute('zfcadmin/user');
            }
        }

        return $this->getViewModel(array('form' => $form));
    }

    public function userAction()
    {
        $user = $this->getUser();
        return $this->getViewModel(array('user' => $user));
    }

    public function setPasswordAction()
    {
        $user = $this->getUser();
        $form = new SetPasswordForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $factory = new UserFactory($this->getServiceLocator());
                $factory->setPassword($user, $data['password1']);
                $em = $this->getEntityManager();
                $em->persist($user);
                $em->flush();
                $this->flashMessenger()->addMessage('Neues Passwort gesetzt.');
                return $this->redirect()->toRoute('zfcadmin/user/user', array('user_id' => $user->getId()));
            }
        }

        return $this->getViewModel(
            array(
                'user' => $user,
                'form' => $form,
            )
        );
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return $this->getEntityManager()->find('Midnight\User\Entity\User', $this->params()->fromRoute('user_id'));
    }

    private function getViewModel($variables = null)
    {
        $vm = new ViewModel($variables);
        $vm->setTemplate('user/user-admin/' . $this->params()->fromRoute('action') . '.phtml');
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
