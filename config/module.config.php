<?php

namespace Midnight\UserModule;

use Midnight\UserModule\Controller\UserConsoleController;
use Midnight\UserModule\Controller\UserConsoleControllerFactory;

return [
    'router' => [
        'routes' => [
            'user' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/user',
                ],
                'child_routes' => [
                    'signup' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/register',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Authentication',
                                'action' => 'register',
                            ],
                        ],
                    ],
                    'logout' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/logout',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Authentication',
                                'action' => 'logout',
                            ],
                        ],
                    ],
                    'login' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/login',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Authentication',
                                'action' => 'login',
                            ],
                        ],
                    ],
                ],
            ],
            'zfcadmin' => [
                'child_routes' => [
                    'user' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/users',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\\UserAdmin',
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'edit' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/:identity/edit',
                                    'defaults' => [
                                        'action' => 'edit-user',
                                    ],
                                ],
                            ],
                            'set_password' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/:identity/set-password',
                                    'defaults' => ['action' => 'set-password'],
                                ],
                            ],
                            'create_user' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/create-user',
                                    'defaults' => ['action' => 'create-user'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'create-user' => [
                    'options' => [
                        'route' => 'create user --email=',
                        'defaults' => [
                            'controller' => UserConsoleController::class,
                            'action' => 'create-user',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            __NAMESPACE__ . '\Authentication' => __NAMESPACE__ . '\Controller\AuthenticationController',
            __NAMESPACE__ . '\UserAdmin' => __NAMESPACE__ . '\Controller\UserAdminController',
        ],
        'factories' => [
            UserConsoleController::class => UserConsoleControllerFactory::class
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'user.crypt' => 'Midnight\UserModule\Crypt\Bcrypt',
            'user.login.form' => 'Midnight\UserModule\Form\LoginForm',
            'user.signup.form' => 'Midnight\UserModule\Form\SignupForm',
            'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService'
        ],
        'factories' => [
            'user.authentication.adapter' => 'Midnight\UserModule\Authentication\AdapterFactory',
            'user.service' => 'Midnight\UserModule\Service\UserServiceFactory',
            'user.storage' => 'Midnight\UserModule\Storage\StorageFactory',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ => [
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'cache' => 'array',
                'paths' => [
                    dirname(__DIR__) . '/mapping/',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'Midnight\UserModule\Entity' => __NAMESPACE__
                ],
            ],
        ],
    ],
    'user' => [
        'post_login_route' => 'home',
        'post_logout_route' => 'home',
    ],
    'navigation' => [
        'admin' => [
            'users' => [
                'label' => 'Users',
                'route' => 'zfcadmin/user'
            ],
            'logout' => [
                'label' => 'Log out',
                'route' => 'user/logout'
            ]
        ],
    ],
    'zfc_rbac' => [
        'role_provider' => [
            'ZfcRbac\Role\InMemoryRoleProvider' => [
                'admin' => [
                    'children' => ['member']
                ],
                'user' => []
            ],
        ],
        'redirect_strategy' => [
            'redirect_when_connected' => false,
            'redirect_to_route_connected' => '',
            'redirect_to_route_disconnected' => 'user/login',
            'append_previous_uri' => true,
            'previous_uri_query_key' => 'next'
        ],
    ],
];
