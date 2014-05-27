<?php

namespace Midnight\UserModule;

return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/user',
                ),
                'child_routes' => array(
                    'signup' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Authentication',
                                'action' => 'register',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Authentication',
                                'action' => 'logout',
                            ),
                        ),
                    ),
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Authentication',
                                'action' => 'login',
                            ),
                        ),
                    ),
                ),
            ),
            'zfcadmin' => array(
                'child_routes' => array(
                    'user' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/users',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\\UserAdmin',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Authentication' => __NAMESPACE__ . '\Controller\AuthenticationController',
            __NAMESPACE__ . '\UserAdmin' => __NAMESPACE__ . '\Controller\UserAdminController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'user.crypt' => 'Midnight\UserModule\Crypt\Bcrypt',
            'user.login.form' => 'Midnight\UserModule\Form\LoginForm',
            'user.signup.form' => 'Midnight\UserModule\Form\SignupForm',
            'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService'
        ),
        'factories' => array(
            'user.authentication.adapter' => 'Midnight\UserModule\Authentication\AdapterFactory',
            'user.service' => 'Midnight\UserModule\Service\UserServiceFactory',
            'user.storage' => 'Midnight\UserModule\Storage\StorageFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            dirname(__DIR__) . '/view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'cache' => 'array',
                'paths' => array(
                    dirname(__DIR__) . '/mapping/',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Midnight\UserModule\Entity' => __NAMESPACE__
                ),
            ),
        ),
    ),
    'user' => array(
        'post_login_route' => 'home',
        'post_logout_route' => 'home',
    ),
    'navigation' => array(
        'admin' => array(
            'users' => array(
                'label' => 'Users',
                'route' => 'zfcadmin/user'
            ),
            'logout' => array(
                'label' => 'Log out',
                'route' => 'user/logout'
            )
        ),
    ),
    'zfc_rbac' => array(
        'role_provider' => array(
            'ZfcRbac\Role\InMemoryRoleProvider' => array(
                'admin' => array(
                    'children' => array('member')
                ),
                'user' => array()
            ),
        ),
        'redirect_strategy' => array(
            'redirect_when_connected' => false,
            'redirect_to_route_connected' => '',
            'redirect_to_route_disconnected' => 'user/login',
            'append_previous_uri' => true,
            'previous_uri_query_key' => 'next'
        ),
    ),
);
