<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Settings\Controller\UserShortCut'       => 'Settings\Controller\UserShortCutController',
            )
        ),
    'router' => array(
        'routes' => array(
            'settings' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/settings',
                    'defaults' => array(
                        'controller' => 'Settings\Controller\UserShortCut',
                        'action' => 'index'
                        )
                    ),
                'may_terminate' => true,
                'child_routes' => array(
                    'user_shortcut' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'    => '/user-shortcut[/:action][/:user][/:module]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'user'     => '[a-zA-Z0-9]+',
                                'module'     => '[0-9]+',
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
        'settings' => __DIR__ . '/../view'
        )
    ),
);