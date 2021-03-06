<?php

namespace Midnight\User\Factory;

use Midnight\User\Entity\User;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserFactory implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    public function __construct(ServiceLocatorInterface $sl)
    {
        $this->setServiceLocator($sl);
    }

    /**
     * @param string $email
     * @param string $password
     * @return \Midnight\User\Entity\User
     */
    public function create($email, $password)
    {
        $user = new User();
        $user->setEmail($email);
        $this->setPassword($user, $password);
        return $user;
    }

    /**
     * @param User $user
     * @param string $password
     * @throws \Exception
     */
    public function setPassword(User $user, $password)
    {
        /** @var \Zend\Crypt\Password\PasswordInterface $generator */
        $generator = $this->getServiceLocator()->get('password_hash_generator');
        if (!$generator instanceof \Zend\Crypt\Password\PasswordInterface) {
            throw new \Exception('Expected to get an implementation of Zend\Crypt\Password\PasswordInterface from the Service Manager. Got ' . get_class(
                $generator
            ));
        }
        $password_hash = $generator->create($password);
        $user->setPasswordHash($password_hash);
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