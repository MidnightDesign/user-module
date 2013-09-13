<?php

namespace Midnight\User;

use Zend\Crypt\Password\Bcrypt;
use Zend\ServiceManager\ServiceLocatorInterface;

return array(
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'user' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/users',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\UserAdmin',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'create' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/create',
                                    'defaults' => array('action' => 'create'),
                                ),
                            ),
                            'user' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:user_id',
                                    'defaults' => array('action' => 'user'),
                                    'constraints' => array('user_id' => '\d+'),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'user' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/user',
                ),
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\Authentication',
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\Authentication',
                                'action' => 'logout',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Controller\UserAdmin' => __NAMESPACE__ . '\Controller\UserAdminController',
            __NAMESPACE__ . '\Controller\Authentication' => __NAMESPACE__ . '\Controller\AuthenticationController',
        ),
    ),
    'navigation' => array(
        'admin' => array(
            __NAMESPACE__ => array(
                'label' => 'Benutzer',
                'route' => 'zfcadmin/user',
            ),
            'logout' => array(
                'label' => 'Logout',
                'route' => 'user/logout',
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Midnight/User/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Midnight\User\Entity' => __NAMESPACE__,
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'password_hash_generator' => function (ServiceLocatorInterface $sl) {
                return new Bcrypt();
            },
        ),
        'invokables' => array(
            'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService',
            'Midnight\User\Authentication/Adapter' => 'Midnight\User\Authentication\Adapter',
        ),
        'aliases' => array(
            'auth' => 'Zend\Authentication\AuthenticationService',
        ),
    ),
);