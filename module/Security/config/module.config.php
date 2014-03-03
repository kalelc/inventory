<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Security\Controller\Session' => 'Security\Controller\SessionController',
            'Security\Controller\Rol' => 'Security\Controller\RolController',
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