<?php

namespace Midnight\UserModule\Storage;

use Doctrine\Common\Persistence\ObjectManager;
use Midnight\User\Storage\Doctrine;
use Midnight\User\Storage\StorageInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StorageFactory implements FactoryInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $sl;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return StorageInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator;
        $storage = new Doctrine();
        $storage->setObjectManager($this->getObjectManager($serviceLocator));
        $storage->setClassName('Midnight\UserModule\Entity\User');
        return $storage;
    }

    /**
     * @return ObjectManager
     */
    private function getObjectManager()
    {
        return $this->sl->get('doctrine.entitymanager.orm_default');
    }
}
