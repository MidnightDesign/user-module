<?php

namespace Midnight\User;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Crypt\Password\Bcrypt;

return [
    'router' => [
        'routes' => [
            'zfcadmin' => [
                'child_routes' => [
                    'user' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/users',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\UserAdmin',
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'create' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/create',
                                    'defaults' => ['action' => 'create'],
                                ],
                            ],
                            'user' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/:user_id',
                                    'defaults' => ['action' => 'user'],
                                    'constraints' => ['user_id' => '\d+'],
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    'set_password' => [
                                        'type' => 'Literal',
                                        'options' => [
                                            'route' => '/set-password',
                                            'defaults' => ['action' => 'set-password'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'user' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/user',
                ],
                'child_routes' => [
                    'login' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/login',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\Authentication',
                                'action' => 'login',
                            ],
                        ],
                    ],
                    'logout' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/logout',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\Authentication',
                                'action' => 'logout',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            __NAMESPACE__ . '\Controller\UserAdmin' => __NAMESPACE__ . '\Controller\UserAdminController',
            __NAMESPACE__ . '\Controller\Authentication' => __NAMESPACE__ . '\Controller\AuthenticationController',
        ],
    ],
    'navigation' => [
        'admin' => [
            __NAMESPACE__ => [
                'label' => 'Benutzer',
                'route' => 'zfcadmin/user',
                'order' => 500,
            ],
            'logout' => [
                'label' => 'Logout',
                'route' => 'user/logout',
                'order' => 9999,
            ],
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Midnight/User/Entity',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'Midnight\User\Entity' => __NAMESPACE__,
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'password_hash_generator' => function () {
                return new Bcrypt();
            },
        ],
        'invokables' => [
            'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService',
            'Midnight\User\Authentication/Adapter' => 'Midnight\User\Authentication\Adapter',
        ],
        'aliases' => [
            'auth' => 'Zend\Authentication\AuthenticationService',
        ],
    ],
];
