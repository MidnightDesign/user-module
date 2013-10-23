<?php

namespace Midnight\User\Authentication;

use Doctrine\ORM\EntityManager;
use Midnight\User\Entity\User;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Adapter
 * @package Midnight\User\Authentication
 *
 * @method \Midnight\User\Entity\User getIdentity() getIdentity()
 */
class Adapter extends AbstractAdapter implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        $user = $this->getUser();
        if (!$user) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $this->getIdentity());
        }
        $password = $this->getCredential();
        /** @var $hash_generator Bcrypt */
        $hash_generator = $this->getServiceLocator()->get('password_hash_generator');
        if (!$hash_generator->verify($password, $user->getPasswordHash())) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, $this->getIdentity());
        }
        return new Result(Result::SUCCESS, $user);
    }

    /**
     * @return User|null
     */
    private function getUser()
    {
        /** @var $em EntityManager */
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        /** @var $user User */
        $user = $em->getRepository('Midnight\User\Entity\User')->findOneBy(array('email' => $this->getIdentity()));
        return $user;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }
}