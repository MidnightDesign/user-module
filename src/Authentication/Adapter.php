<?php

namespace Midnight\UserModule\Authentication;

use Midnight\User\Service\CryptoServiceInterface;
use Midnight\User\Storage\StorageInterface;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class Adapter implements AdapterInterface
{
    /**
     * @var string
     */
    private $identity;
    /**
     * @var string
     */
    private $credential;
    /**
     * @var StorageInterface
     */
    private $storage;
    /**
     * @var CryptoServiceInterface
     */
    private $crypt;

    /**
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        $email = $this->identity;
        $user = $this->storage->loadByIdentity($email);
        if (!$user) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $email);
        }
        if (!$this->crypt->verify($this->credential, $user->getCredential())) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, $user);
        }
        return new Result(Result::SUCCESS, $user);
    }

    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param CryptoServiceInterface $crypt
     */
    public function setCrypt(CryptoServiceInterface $crypt)
    {
        $this->crypt = $crypt;
    }

    /**
     * @param string $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @param string $credential
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
    }
}
