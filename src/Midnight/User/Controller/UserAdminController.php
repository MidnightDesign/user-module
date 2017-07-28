<?php

namespace Midnight\User\Controller;

use Doctrine\ORM\EntityManager;
use Midnight\User\Entity\User;
use Midnight\User\Factory\UserFactory;
use Midnight\User\Form\CreateUserForm;
use Midnight\User\Form\SetPasswordForm;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserAdminController extends AbstractActionController
{
    public function indexAction()
    {
        $users = $this->getEntityManager()->getRepository(User::class)->findAll();
        return $this->getViewModel(['users' => $users]);
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

        return $this->getViewModel(['form' => $form]);
    }

    public function userAction()
    {
        $user = $this->getUser();
        return $this->getViewModel(['user' => $user]);
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
                return $this->redirect()->toRoute('zfcadmin/user/user', ['user_id' => $user->getId()]);
            }
        }

        return $this->getViewModel(
            [
                'user' => $user,
                'form' => $form,
            ]
        );
    }

    /**
     * @return User
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Zend\Mvc\Exception\RuntimeException
     */
    private function getUser()
    {
        return $this->getEntityManager()->find(User::class, $this->params()->fromRoute('user_id'));
    }

    private function getViewModel($variables = null)
    {
        $vm = new ViewModel($variables);
        $vm->setTemplate('user/user-admin/' . $this->params()->fromRoute('action') . '.phtml');
        return $vm;
    }

    /**
     * @return EntityManager
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }
}
