<?php

namespace Midnight\UserModule\Role;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RoleProviderFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RoleProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RoleProvider();
    }
}
