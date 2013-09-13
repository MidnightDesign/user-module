<?php

namespace Midnight\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Permissions\Acl\Role\RoleInterface;
use ZfcUser\Entity\UserInterface;

/**
 * Class User
 * @package Midnight\User\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements UserInterface, RoleInterface, \ZfcUser\Entity\UserInterface
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column
     */
    private $email;
    /**
     * @var string
     *
     * @ORM\Column
     */
    private $password_hash;
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $is_admin = false;

    /**
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->is_admin;
    }

    /**
     * @param boolean $is_admin
     */
    public function setIsAdmin($is_admin)
    {
        $this->is_admin = $is_admin;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    /**
     * @param string $password_hash
     */
    public function setPasswordHash($password_hash)
    {
        $this->password_hash = $password_hash;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getEmail();
    }

    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId()
    {
        if ($this->getIsAdmin()) {
            return self::ROLE_ADMIN;
        }
        return self::ROLE_USER;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @throws \Exception
     * @return UserInterface
     */
    public function setId($id)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Get username.
     *
     * @throws \Exception
     * @return string
     */
    public function getUsername()
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Set username.
     *
     * @param string $username
     * @throws \Exception
     * @return UserInterface
     */
    public function setUsername($username)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Get displayName.
     *
     * @throws \Exception
     * @return string
     */
    public function getDisplayName()
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     * @throws \Exception
     * @return UserInterface
     */
    public function setDisplayName($displayName)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Get password.
     *
     * @return string password
     */
    public function getPassword()
    {
        return $this->getPasswordHash();
    }

    /**
     * Set password.
     *
     * @param string $password
     * @return UserInterface
     */
    public function setPassword($password)
    {
        $this->setPasswordHash($password);
    }

    /**
     * Get state.
     *
     * @throws \Exception
     * @return int
     */
    public function getState()
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Set state.
     *
     * @param int $state
     * @throws \Exception
     * @return UserInterface
     */
    public function setState($state)
    {
        throw new \Exception('Not implemented');
    }
}