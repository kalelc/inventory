<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Process\Controller\ReceiveInventory' 		=> 'Process\Controller\ReceiveInventoryController',
			),
		),
	'router' => array(
		'routes' => array(
			'admin' => array(
				'type' => 'literal',
				'options' => array(
					'route' => '/process',
					'defaults' => array(
						'controller' => 'Security\Controller\Session',
						'action' => 'index'
						)
					),
				'may_terminate' => true,
				'child_routes' => array(
					'receive_inventory' => array(
						'type'    => 'segment',
						'options' => array(
							'route'    => '/receive-inventory[/:action][/:id]',
							'constraints' => array(
								'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'     => '[0-9]+',
								),
							'defaults' => array(
								'controller' => 'Process\Controller\ReceiveInventory',
								'action'     => 'index',
								),
							),
						),
					),
				),
			),
		),

	'view_helpers' => array(),
	'view_manager' => array(
		'template_path_stack' => array(
			'process' => __DIR__ . '/../view',
			),
		),
	);