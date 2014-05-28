<?php

namespace Midnight\UserModule\Authentication;

use Midnight\User\Service\CryptoServiceInterface;
use Midnight\User\Storage\StorageInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterFactory implements FactoryInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $sl;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return AdapterInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator;
        $adapter = new Adapter();
        $adapter->setStorage($this->getStorage());
        $adapter->setCrypt($this->getCrypt());
        return $adapter;
    }

    /**
     * @return StorageInterface
     */
    private function getStorage()
    {
        return $this->sl->get('user.storage');
    }

    /**
     * @return CryptoServiceInterface
     */
    private function getCrypt()
    {
        return $this->sl->get('user.crypt');
    }
}
