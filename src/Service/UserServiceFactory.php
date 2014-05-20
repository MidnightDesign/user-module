<?php

namespace Midnight\UserModule\Service;

use Midnight\User\Service\CryptoServiceInterface;
use Midnight\User\Service\UserService;
use Midnight\User\Storage\StorageInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserServiceFactory implements FactoryInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $sl;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator;
        $cryptoService = $this->getCryptoService($serviceLocator);
        $storage = $this->getStorage();
        return new UserService($cryptoService, $storage);
    }

    /**
     * @return CryptoServiceInterface
     */
    private function getCryptoService()
    {
        return $this->sl->get('user.crypt');
    }

    /**
     * @return StorageInterface
     */
    private function getStorage()
    {
        return $this->sl->get('user.storage');
    }
}
