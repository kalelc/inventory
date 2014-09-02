<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Security\Controller\Session'   => 'Security\Controller\SessionController',
            'Security\Controller\Rol'       => 'Security\Controller\RolController',
            'Security\Controller\User'      => 'Security\Controller\UserController',
            'Security\Controller\Module'    => 'Security\Controller\ModuleController',
            )
        ),
    'router' => array(
        'routes' => array(
            'security' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/security',
                    'defaults' => array(
                        'controller' => 'Security\Controller\Session',
                        'action' => 'index'
                        )
                    ),
                'may_terminate' => true,
                'child_routes' => array(
                    'user' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/user[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                                ),
                            'defaults' => array(
                                'controller' => 'Security\Controller\User',
                                'action'     => 'index',
                                ),
                            ),
                        ),
                    'rol' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/rol[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                                ),
                            'defaults' => array(
                                'controller' => 'Security\Controller\Rol',
                                'action'     => 'index',
                                ),
                            ),
                        ),
                    'module' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/module[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                                ),
                            'defaults' => array(
                                'controller' => 'Security\Controller\Module',
                                'action'     => 'index',
                                ),
                            ),
                        ),
                    'login' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'action' => 'login'
                                )
                            ),
                        ),
                    'logout' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'action' => 'logout'
                                )
                            ),
                        ),
                    'acl' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/acl',
                            'defaults' => array(
                                'action' => 'acl'
                                )
                            ),
                        ),
                    ),
                ),
            ),
        ),
'translator' => array(
    'locale' => 'es_CO',
    'translation_file_patterns' => array(
        array(
            'type' => 'gettext',
            'base_dir' => __DIR__ . '/../language',
            'pattern' => '%s.mo'
            )
        )
    ),
'view_manager' => array(
    'template_path_stack' => array(
        'security' => __DIR__ . '/../view'
        )
    ),
);