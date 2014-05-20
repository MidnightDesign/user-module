<?php

namespace Midnight\UserModule\Role;

use Rbac\Role\Role;
use Zend\Permissions\Rbac\RoleInterface;
use ZfcRbac\Role\RoleProviderInterface;
use ZfcRbac\Service\RbacEvent;

class RoleProvider implements RoleProviderInterface
{
    /**
     * @param  RbacEvent $event
     *
     * @return string[]|array|RoleInterface[]
     */
    public function getRoles(array $roles)
    {
        $r = array();
        foreach($roles as $role) {
            $r[] = new Role($role);
        }
        return $r;
    }
}
