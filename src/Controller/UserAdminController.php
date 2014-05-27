<?php

namespace Midnight\UserModule\Controller;

use Midnight\User\Storage\StorageInterface;
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

    /**
     * @return StorageInterface
     */
    private function getUserStorage()
    {
        return $this->getServiceLocator()->get('user.storage');
    }
} 
