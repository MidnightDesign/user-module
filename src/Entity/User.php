<?php

namespace Midnight\UserModule\Entity;

use ZfcRbac\Identity\IdentityInterface;

class User extends \Midnight\User\Entity\User implements IdentityInterface
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var boolean
     */
    protected $isAdmin = false;

    /**
     * Get the list of roles of this identity
     *
     * @return string[]|\Rbac\Role\RoleInterface[]
     */
    public function getRoles()
    {
        $roles = array('user');
        if ($this->isAdmin) {
            $roles[] = 'admin';
        }
        return $roles;
    }
}
