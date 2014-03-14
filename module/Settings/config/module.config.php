<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Settings\Controller\UserShortCut'       => 'Settings\Controller\UserShortCutController',
            )
        ),
    'router' => array(
        'routes' => array(
            'security' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/settings',
                    'defaults' => array(
                        'controller' => 'Settings\Controller\Session',
                        'action' => 'index'
                        )
                    ),
                'may_terminate' => true,
                'child_routes' => array(
                    'user_shortcut' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/user-shortcut[/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]+',
                                ),
                            'defaults' => array(
                                'controller' => 'Settings\Controller\UserShortCut',
                                'action'     => 'index',
                                ),
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