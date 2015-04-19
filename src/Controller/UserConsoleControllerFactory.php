<?php

namespace Midnight\UserModule\Controller;

use Midnight\User\Service\UserService;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserConsoleControllerFactory
 *
 * @package Midnight\UserModule\Controller
 */
class UserConsoleControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserConsoleController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator instanceof ControllerManager) {
            throw new \LogicException;
        }
        $sl = $serviceLocator->getServiceLocator();
        return new UserConsoleController($this->getUserService($sl));
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @return UserService
     */
    private function getUserService(ServiceLocatorInterface $sl)
    {
        return $sl->get('user.service');
    }
}