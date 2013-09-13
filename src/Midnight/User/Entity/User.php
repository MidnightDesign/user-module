<?php

namespace Midnight\User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package Midnight\User\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
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
}