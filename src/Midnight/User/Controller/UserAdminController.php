<?php

namespace Midnight\User\Controller;

use Doctrine\ORM\EntityManager;
use Midnight\User\Factory\UserFactory;
use Midnight\User\Form\CreateUserForm;
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
        $user = $this->getEntityManager()->find('Midnight\User\Entity\User', $this->params()->fromRoute('user_id'));
        return $this->getViewModel(array('user' => $user));
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